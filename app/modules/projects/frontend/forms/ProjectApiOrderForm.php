<?php

namespace modules\projects\frontend\forms;

use marketingsolutions\finance\models\Purse;
use modules\profiles\common\models\Profile;
use modules\projects\common\models\Project;
use ms\loyalty\catalog\frontend\api\v3\forms\ApiOrderForm;
use ms\loyalty\finances\common\models\Transaction;
use Yii;

class ProjectApiOrderForm extends ApiOrderForm
{
    /** @var null|Purse */
    protected $purse = null;

    public function validateAmount()
    {
        $profileId = $this->getPrizeRecipient()->recipientId;

        if ($purse_id = (int) Yii::$app->request->post('purse_id')) {
            if ($purse = Purse::findOne($purse_id)) {
                if ($purse->owner_type === Profile::class && $purse->owner_id === $profileId) {
                    $this->purse = $purse;
                }
            }
        }

        if (null === $this->purse) {
            $this->addError('amount', 'Пожалуйста, укажите свой кошелек');
        }

        if ($this->profile_amount > $this->purse->balance) {
            $this->addError('amount', 'На указанном кошельке недостаточно средств');
        }

        $this->project_id = $this->purse->project_id;
    }

    /**
     * Bonuses spending from user account.
     */
    protected function payBonuses()
    {
        $this->purse->addTransaction(Transaction::factory(
            Transaction::OUTBOUND,
            $this->profile_amount,
            $this,
            'Заказ ЭПС №' . $this->id
        ));
    }

    public function create()
    {
        if (parent::create()) {
            $project = Project::findOne($this->project_id);
            $uid = strtoupper($project->id1c) . '-EPS-' . $this->id;
            $this->updateAttributes(['uid' => $uid]);
            return true;
        }

        return false;
    }
}