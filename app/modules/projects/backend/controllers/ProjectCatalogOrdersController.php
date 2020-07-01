<?php

namespace modules\projects\backend\controllers;

use marketingsolutions\finance\models\Purse;
use marketingsolutions\finance\models\Transaction;
use modules\profiles\common\models\Profile;
use modules\projects\common\models\Project;
use ms\loyalty\catalog\backend\controllers\CatalogOrdersController;
use ms\loyalty\catalog\backend\models\CatalogOrderSearch;
use ms\loyalty\catalog\common\CatalogModuleTrait;
use ms\loyalty\catalog\common\finances\RevertCatalogOrderPartner;
use ms\loyalty\catalog\common\models\CatalogOrder;
use ms\loyalty\catalog\common\models\OrderedCard;
use ms\loyalty\catalog\common\models\ZakazpodarkaOrder;
use ms\loyalty\finances\common\components\CompanyAccount;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yz\admin\actions\ExportAction;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\grid\columns\DataColumn;
use yz\admin\traits\CheckAccessTrait;

/**
 * CatalogOrdersController implements the CRUD actions for CatalogOrder model.
 */
class ProjectCatalogOrdersController extends CatalogOrdersController implements AccessControlInterface
{
    
    public function actionRollback($id)
    {
        $order = $this->findModel($id);
        $project = Project::findone($order->project_id);
        /** @var Purse $companyPurse */
        $companyPurse =$project->purse;
        /** @var Profile $profile */
        $profile = Profile::findOne($order->user_id);
        $purse = $profile->getMultipurse($project);
        
        $zakazpodarkaOrder = ZakazpodarkaOrder::find()->where(['zp_order_id' => $order->id1c])->one();
        
        # добавить баллы на счет участника
        $transaction = Transaction::findOne([
            'purse_id' => $purse->id,
            'partner_type' => 'modules\projects\frontend\forms\ProjectApiOrderForm',
            'partner_id' => $id,
        ]);
        
        if ($transaction) {
            $purse->addTransaction(Transaction::factory(
                Transaction::OUTBOUND,
                -$transaction->amount,
                new RevertCatalogOrderPartner(['id' => $order->id]),
                "Возврат заказа ЭПС #$id участнику ({$profile->full_name}) {$project->title}"
            ), true, false);
        }
        
        # убавить с доходного аккаунта
        if (class_exists('\ms\loyalty\taxes\common\models\Account')) {
            /** @var \ms\loyalty\taxes\common\models\Account $account */
            if ($account = \ms\loyalty\taxes\common\models\Account::findOne(['profile_id' => $profile->id])) {
                $transaction = Transaction::findOne([
                    'purse_id' => $purse->id,
                    'partner_type' => 'ms\loyalty\catalog\common\models\CatalogOrder',
                    'partner_id' => $id,
                ]);
                if ($transaction) {
                    $account->purse->addTransaction(Transaction::factory(
                        Transaction::INCOMING,
                        -$transaction->amount,
                        new RevertCatalogOrderPartner(['id' => $order->id]),
                        "Откат заказа ЭПС #$id налоговому участнику ({$profile->full_name})"
                    ));
                }
            }
        }
        
        # добавить на счет компании
        $transaction = Transaction::findOne([
            'purse_id' => $companyPurse->id,
            'partner_type' => 'ms\loyalty\catalog\common\components\ZakazpodarkaOrderPartner',
            'partner_id' => $zakazpodarkaOrder->id,
        ]);
        if ($transaction) {
            $companyPurse->addTransaction(Transaction::factory(
                Transaction::OUTBOUND,
                -$transaction->amount,
                new RevertCatalogOrderPartner(['id' => $order->id]),
                "Откат заказа ЭПС #$id для счета компании"
            ));
        }
        
        $order->updateAttributes(['status' => CatalogOrder::STATUS_ROLLBACK]);
        
        $orderedCards = OrderedCard::findAll(['catalog_order_id' => $order->id]);
        
        foreach ($orderedCards as $orderedCard) {
            $orderedCard->updateAttributes(['status' => CatalogOrder::STATUS_ROLLBACK]);
            $orderedCard->trigger(OrderedCard::EVENT_STATUS_CHANGED);
            /** @var ZakazpodarkaOrder $zakazpodarkaOrder */
            if ($zakazpodarkaOrder = ZakazpodarkaOrder::findOne($orderedCard->zakazpodarka_order_id)) {
                \Yii::$app->session->setFlash(\yz\Yz::FLASH_WARNING, "Пожалуйста, убедитесь, что в 1С также заблокирован заказ № {$zakazpodarkaOrder->zp_order_id}");
            }
        }
        
        \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, "Заказ ЭПС #$id откачен");
        $order->updateAttributes(['updated_at' => (new \DateTime)->format('Y-m-d H:i:s')]);
        
        return $this->redirect(\Yii::$app->request->referrer);
    }
    
}
