<?php


namespace modules\projects\backend\controllers;


use modules\profiles\common\models\Profile;
use modules\projects\common\models\Project;
use ms\loyalty\prizes\payments\backend\controllers\PaymentsController;
use ms\loyalty\prizes\payments\backend\models\PaymentSearch;
use ms\loyalty\prizes\payments\common\commands\CancelPaymentCommand;
use ms\loyalty\prizes\payments\common\finances\PaymentPartner;
use ms\loyalty\prizes\payments\common\finances\RevertPaymentPartner;
use ms\loyalty\prizes\payments\common\models\Payment;
use ms\loyalty\prizes\payments\common\models\PaymentRequest;
use marketingsolutions\finance\models\Purse;
use ms\loyalty\prizes\payments\common\models\PaymentsConfig;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yz\admin\actions\ExportAction;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\grid\filters\DateRangeFilter;
use yz\admin\grid\columns\DataColumn;
use marketingsolutions\finance\models\Transaction;
use yz\admin\models\User;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\traits\CrudTrait;

/**
 * PaymentsController implements the CRUD actions for Payment model.
 */
class ProjectPaymentsController extends PaymentsController implements AccessControlInterface
{
    public function actionRollback($id)
    {
        $payment = $this->findModel($id);
        $project = Project::findone($payment->project_id);
        /** @var Purse $companyPurse */
        $companyPurse =$project->purse;
        /** @var Profile $profile */
        $profile = Profile::findOne($payment->profile_id);
        $purse = $profile->getMultipurse($project);
        
        # команда блокировки платежа в 1С
        if (!empty($payment->id1c)) {
            $command = new CancelPaymentCommand($payment);
            $command->handle();
        }
        
        # добавить баллы на счет участника
        $transaction = Transaction::findOne([
            'purse_id' => $purse->id,
            'partner_type' => 'ms\loyalty\prizes\payments\common\finances\PaymentPartner',
            'partner_id' => $id,
        ]);
        
        if ($transaction) {
            $purse->addTransaction(Transaction::factory(
                Transaction::OUTBOUND,
                -$transaction->amount,
                new RevertPaymentPartner(['id' => $payment->id]),
                "Возврат платежа #$id участнику ({$profile->full_name}) {$project->title}"
            ));
        }
        
        # убавить с доходного аккаунта
        if (class_exists('\ms\loyalty\taxes\common\models\Account')) {
            /** @var \ms\loyalty\taxes\common\models\Account $account */
            if ($account = \ms\loyalty\taxes\common\models\Account::findOne(['profile_id' => $profile->id])) {
                $transaction = Transaction::findOne([
                    'purse_id' => $account->purse->id,
                    'partner_type' => 'ms\loyalty\prizes\payments\common\finances\PaymentPartner',
                    'partner_id' => $id,
                ]);
                if ($transaction) {
                    $account->purse->addTransaction(Transaction::factory(
                        Transaction::INCOMING,
                        -$transaction->amount,
                        new RevertPaymentPartner(['id' => $payment->id]),
                        "Откат платежа #$id налоговому участнику ({$profile->full_name})"
                    ));
                }
            }
        }
        
        # добавить на счет компании
        $transaction = Transaction::findOne([
            'purse_id' => $companyPurse->id,
            'partner_type' => 'ms\loyalty\prizes\payments\common\finances\PaymentPartner',
            'partner_id' => $id,
        ]);
        if ($transaction) {
            $companyPurse->addTransaction(Transaction::factory(
                Transaction::OUTBOUND,
                -$transaction->amount,
                new RevertPaymentPartner(['id' => $payment->id]),
                "Откат платежа #$id для счета компании"
            ));
        }
        
        $payment->updateAttributes(['status' => Payment::STATUS_ROLLBACK]);
        $payment->trigger(Payment::EVENT_STATUS_CHANGED);
        
        \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, \Yii::t('admin/t', "Платеж #$id откачен"));
        
        return $this->redirect(\Yii::$app->request->referrer);
    }
}
