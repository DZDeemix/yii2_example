<?php

namespace modules\projects\frontend\forms;

use marketingsolutions\finance\models\Purse;
use modules\profiles\common\models\Profile;
use ms\loyalty\finances\common\models\Transaction;
use ms\loyalty\prizes\payments\common\finances\PaymentPartner;
use ms\loyalty\prizes\payments\common\models\Payment;
use ms\loyalty\prizes\payments\common\models\PaymentsConfig;
use \ms\loyalty\prizes\payments\frontend\forms\CreatePaymentForm;
use Yii;
use yii\base\ModelEvent;

class ProjectCreatePaymentForm extends CreatePaymentForm
{
    /** @var null|Purse */
    protected $purse = null;

    public function validateAmount()
    {
        if ($this->hasErrors()) {
            return;
        }

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

        if ($this->getTotalAmount() > $this->purse->balance) {
            $this->addError('amount', 'На указанном кошельке недостаточно средств');
        }
    
        $event = new ModelEvent();
        $this->trigger(self::EVENT_BEFORE_VALIDATE_AMOUNT, $event);
        $maxAmount = Payment::getLimitSumm()[$this->type]["max_summ"];
        $minAmount = Payment::getLimitSumm()[$this->type]["min_summ"];
        # проверка минимальной и максимальной суммы
        $description_error = "(Сумма перевода для данного типа платежа от ".$minAmount."  до ".$maxAmount. " руб.)";
    
    
        if ($maxAmount && $this->amount > $maxAmount) {
            $this->addError('amount', 'Максимальный размер единовременного платежа - ' . $maxAmount ." руб. ".$description_error);
            return false;
        }
    
    
        if ($minAmount && $this->amount < $minAmount) {
            $this->addError('amount', 'Минимальный размер единовременного платежа - ' . $minAmount." руб. ".$description_error);
            return false;
        }
    }

    public function create()
    {
        if ($this->validateAll() === false) {
            return false;
        }

        $transaction = \Yii::$app->db->beginTransaction();

        $this->payment->recipient_id = $this->getPrizeRecipient()->recipientId;
        $this->payment->status = Payment::STATUS_NEW;
        $this->payment->payment_id = $this->payment_id;
        $this->payment->project_id = $this->purse->project_id;

        if (!$this->payment->save(false)) {
            return false;
        }

        $this->purse->addTransaction(Transaction::factory(
            Transaction::OUTBOUND,
            $this->payment->amount,
            new PaymentPartner(['id' => $this->payment->id]),
            'Перевод средств на ' . $this->payment->paymentType->paymentTitle()
        ));

        $transaction->commit();

        return true;
    }
}
