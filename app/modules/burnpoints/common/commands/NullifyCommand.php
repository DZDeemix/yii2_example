<?php


namespace modules\burnpoints\common\commands;


use common\components\interfaces\CommandInterface;
use Exception;
use marketingsolutions\finance\models\Purse;
use modules\burnpoints\common\finances\NullifyPartner;
use modules\burnpoints\common\models\BurnPoint;
use marketingsolutions\finance\models\Transaction;
use modules\profiles\common\models\Profile;
use modules\projects\common\models\Project;
use ms\loyalty\catalog\common\components\ZakazpodarkaOrderPartner;
use ms\loyalty\finances\common\components\CompanyAccount;
use ms\loyalty\mobile\common\models\MobileNotification;
use Yii;

/**
 * Class BurnCommand
 *
 * @package modules\burnpoints\common\commands
 */
class NullifyCommand extends MainBurnCommand implements CommandInterface
{
    /**
     * BurnCommand constructor.
     * @param array $config
     * @throws Exception
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->checkDate = (new \DateTime())->modify("-{$this->settings->days_to_burn} days")
            ->format('Y-m-d H:i:s');
    }

    public function handle()
    {
        if (!empty($transactions = $this->getTransactionsForBurn())) {
            $this->addBurnTransactions($transactions);
        }
    }
    
    /**
     * Returns transaction purse
     *
     * @return Purse
     */
    protected function getTransactionPurse($purse_id)
    {
        return Purse::findone($purse_id);
    }
    
    /**
     * @param array $transactions
     */
    private function addBurnTransactions(array $transactions)
    {
        $site = $_ENV['FRONTEND_SPA'] ?? $_ENV['FRONTEND_WEB'];
        foreach ($transactions as $transaction) {
            $dbTransaction = Yii::$app->db->beginTransaction();
            
            /** @var Transaction $transaction */
            try {
                $t = new Transaction();
                $t->type = Transaction::INCOMING;
                $t->amount = -$transaction->points_to_burn;
                $t->partner_type = NullifyPartner::class;
                $t->partner_id = $transaction->partner_id;
                $t->title = 'Сгорание баллов участника id транзакции: ' . $transaction->id;
                $t->purse_id = $transaction->purse_id;
                $t->save(false);
                
                $purse = $this->getTransactionPurse($transaction->purse_id);
                $profile = Profile::findOne($purse->owner_id);
                
                $purse->balance = $purse->balance - $transaction->points_to_burn;
                $purse->save(false);
                
                /** @var BurnPoint $burnItem */
                $burnItem = Yii::createObject(BurnPoint::class);
                $burnItem->profile_id = $purse->owner_id;
                $burnItem->purse_id = $transaction->purse_id;
                $burnItem->amount = $transaction->points_to_burn;
                $burnItem->transaction_id = $t->id;
                $burnItem->save();
                
                //обнуляем сгораемые баллы в транзакции
                $transaction->points_to_burn = 0;
                $transaction->save();
                
                $dbTransaction->commit();

                if ($this->settings->warning_sms && $profile->phone_mobile) {
                    Yii::$app->sms->send($profile->phone_mobile, strtr($this->settings->sms_nullify, [
                        '{site}' => $site,
                        '{name}' => $profile->full_name,
                        '{amount}' => $transaction->points_to_burn,
                    ]));
                }

                if ($this->settings->warning_push) {
                    MobileNotification::createPush("{$site} - обнуление баллов", $this->settings->push_nullify, $profile->id);
                }

                if ($this->settings->warning_email && $profile->email) {
                    Yii::$app->mailer
                        ->compose('@modules/burnpoints/common/mails/nullify.php', [
                            'name' => $profile->full_name,
                            'amount' => $transaction->points_to_burn,
                            'site' => $site,
                            'template' => $this->settings->email_nullify_template
                        ])
                        ->setSubject($this->settings->email_nullify_subject)
                        ->setTo($profile->email)
                        ->send();
                }

            } catch (Exception $e) {
                $dbTransaction->rollBack();
            }
            
        }

    }
}
