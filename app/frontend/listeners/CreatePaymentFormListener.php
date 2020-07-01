<?php

namespace frontend\listeners;

use Yii;
use yii\base\Event;
use ms\loyalty\prizes\payments\frontend\forms\CreatePaymentForm;
use modules\profiles\common\models\Profile;

class CreatePaymentFormListener
{
    public static function whenBeforeValidateAmount(Event $event)
    {
        /* @var CreatePaymentForm $sender */
        /* @var Profile $profile */

        $sender = $event->sender;
        $profile = $sender->getPrizeRecipient();

        if ($profile->blocked_at || $profile->banned_at) {
            $sender->addError('amount', "Вы не можете тратить бонусные баллы. Учетная запись заблокирована. "
                . (empty($profile->blocked_reason) ? $profile->blocked_reason : $profile->blocked_reason));
        }
    }
}