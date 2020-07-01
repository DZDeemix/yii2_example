<?php


namespace modules\burnpoints\common\commands;


use common\components\interfaces\CommandInterface;
use marketingsolutions\finance\models\Purse;
use marketingsolutions\finance\models\Transaction;
use modules\profiles\common\models\Profile;
use ms\loyalty\mobile\common\models\MobileNotification;
use Yii;

/**
 * Class NotificationCommand
 * @package modules\burnpoints\common\commands
 */
class WarningCommand extends MainBurnCommand implements CommandInterface
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->checkDate = (new \DateTime())
            ->modify("-{$this->settings->days_to_burn} days")
            ->modify("+1 days")
            ->format('Y-m-d H:i:s');
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        if (
            ($countWarnings = $this->settings->count_warnings) &&
            ($this->settings->warning_sms || $this->settings->warning_push || $this->settings->warning_email)
        ) {
            $site = $_ENV['FRONTEND_SPA'] ?? $_ENV['FRONTEND_WEB'];
            
            if (!empty($transactions = $this->getTransactionsForWarning())) {
                foreach ($transactions as $transaction) {
                    /** @var Transaction $transaction */
                    $purse = Purse::findone($transaction->purse_id);
                    $profile = Profile::findOne($purse->owner_id);
                    $amount = $transaction->amount;
                    
                    //дипазон от 1 до введеного значения дней введеного в админке для уведомлений
                    $daysBefore = (new \DateTime($transaction->created_at))
                        ->modify("+{$this->settings->days_to_burn} days")
                        ->diff(new \DateTime())
                        ->format('%d');
    
                    $d = (int)round($this->settings->days_warning / $countWarnings);
                    
                    $daysToWarning = [];
                    for ($i = (int)$this->settings->days_warning; $i > 0; $i = $i-$d) {
                        $daysToWarning[] = $i;
                    }
                    
                    if (in_array($daysBefore, $daysToWarning)) {
                        if ($this->settings->warning_sms && $profile->phone_mobile) {
                            Yii::$app->sms->send($profile->phone_mobile, strtr($this->settings->sms_warning, [
                                '{site}' => $site,
                                '{name}' => $profile->full_name,
                                '{days}' => $daysBefore,
                                '{amount}' => $amount,
                            ]));
                        }
    
                        if ($this->settings->warning_push) {
                            MobileNotification::createPush("{$site} - обнуление баллов", strtr($this->settings->push_warning, [
                                '{days}' => $daysBefore
                            ]), $profile->id);
                        }
    
                        if ($this->settings->warning_email && $profile->email) {
                            try {
                                Yii::$app->mailer
                                    ->compose('@modules/burnpoints/common/mails/warning.php', [
                                        'name' => $profile->full_name,
                                        'amount' => $amount,
                                        'daysBefore' => $daysBefore,
                                        'template' => $this->settings->email_warning_template,
                                        'site' => $site
                                    ])
                                    ->setSubject($this->settings->email_warning_subject)
                                    ->setTo($profile->email)
                                    ->send();
                            } catch (\Exception $e) {
                            
                            }
                        }
                    }
                }
            }
        }
    }
}
