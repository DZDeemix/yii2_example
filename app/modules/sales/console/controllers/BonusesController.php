<?php

namespace modules\sales\console\controllers;

use console\base\Controller;
use modules\profiles\common\models\Profile;
use modules\projects\common\models\Project;
use modules\sales\common\finances\SalePartner;
use modules\sales\common\models\Sale;
use modules\sales\common\models\SaleHistory;
use modules\sales\common\sales\statuses\Statuses;
use marketingsolutions\finance\models\Transaction;
use yii\db\Expression;
use yii\web\View;
use yz\admin\mailer\common\models\Mail;

/**
 * Class BonusesController
 */
class BonusesController extends Controller
{
    /** @var string date from, example: 01.01.2017 (adds 00:00:00) */
    public $from = null;

    /** @var string date to, example: 01.01.2018 (adds 23:59:59) */
    public $to = null;

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), ['from', 'to']);
    }

    public function actionInitPrimary()
    {
        $query = Sale::find()->orderBy(['id' => SORT_DESC]);

        if ($this->from) {
            $from = (new \DateTime($this->from))->format('Y-m-d 00:00:00');
            $query->andWhere(['>', 'created_at', $from]);
        }

        if ($this->to) {
            $to = (new \DateTime($this->to))->format('Y-m-d 23:59:59');
            $query->andWhere(['<', 'created_at', $to]);
        }

        foreach ($query->each() as $sale) {
            /** @var Sale $sale */
            foreach ($sale->positions as $position) {
                if ($product = $position->product) {
                    $position->bonuses_primary = $product->bonuses_formula;
                    $position->updateAttributes(['bonuses_primary']);
                }
            }
        }
    }

    public function actionUpdate()
    {
        $query = Sale::find()->orderBy(['id' => SORT_DESC]);

        if ($this->from) {
            $from = (new \DateTime($this->from))->format('Y-m-d 00:00:00');
            $query->andWhere(['>', 'created_at', $from]);
        }

        if ($this->to) {
            $to = (new \DateTime($this->to))->format('Y-m-d 23:59:59');
            $query->andWhere(['<', 'created_at', $to]);
        }

        foreach ($query->each() as $sale) {
            /** @var Sale $sale */
            $old_bonuses = $sale->bonuses;
            $sale->updateBonuses(false);

            if ($old_bonuses < $sale->bonuses) {
                echo "#{$sale->id} --- $old_bonuses => $sale->bonuses" . PHP_EOL;

                /** @var Profile $profile */
                $profile = Profile::findOne($sale->recipient_id);
                $diff = $sale->bonuses - $old_bonuses;

                $profile->purse->addTransaction(Transaction::factory(
                    Transaction::INCOMING,
                    $diff,
                    new SalePartner(['id' => $sale->id]),
                    'Доплата после перерасчета за покупку #' . $sale->id
                ), true, false);
            }

            foreach ($sale->positions as $position) {
                $position->updateBonuses();
            }
            $sale->updateBonuses();
        }
    }

    public function actionPay()
    {
        $query = Sale::find()
            ->where(['status' => Statuses::PAID, 'bonuses_paid_at' => null])
            ->andWhere('bonuses > 0');

        foreach ($query->each() as $sale) {
            /** @var Sale $sale */
            $this->stdout('... Try to pay sale #' . $sale->id . PHP_EOL);
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                /** @var Profile $profile */
                $profile = Profile::findOne($sale->recipient_id);
                /** @var Project $profile */
                $project = Project::findOne($sale->project_id);
                $purse = $profile->getMultipurse($project);

                if ($profile == null || empty($purse)) {
                    continue;
                }

                $transTitle = 'Бонусы за продажу #' . $sale->id . ' ' . $sale->project->title;

                if (Transaction::findOne(['title' => $transTitle])) {
                    continue;
                }

                # Добавляем баллы участнику
                $purse->addTransaction(Transaction::factory(
                    Transaction::INCOMING,
                    $sale->bonuses,
                    new SalePartner(['id' => $sale->id]),
                    $transTitle
                ), true, false);

                $sale->updateAttributes([
                    'bonuses_paid_at' => new Expression('NOW()'),
                ]);

                $oldStatus = $sale->status;
                $h = new SaleHistory();
                $h->sale_id = $sale->id;
                $h->status_old = $oldStatus;
                $h->status_new = $sale->status;
                $h->note = 'Продажа подтверждена и выплачена';
                $h->comment = null;
                $h->type = SaleHistory::TYPE_PAY;
                $h->save(false);

                # Создаем письмо с уведомлением о начислении
                $bodyHtml = (new View())->render('@modules/sales/common/mail/sale_paid', compact('sale', 'profile'));
                Mail::add($profile->email, 'Продажа одобрена, баллы начислены', $bodyHtml, $sale);

                $transaction->commit();
                $this->stdout('=> PAID sale #' . $sale->id . PHP_EOL);
            }
            catch (\Exception $e) {
                $transaction->rollBack();
                $this->stdout('=> Failed: ' . $e->__toString() . PHP_EOL);
            }
        }
    }
}
