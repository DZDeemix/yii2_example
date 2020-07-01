<?php

namespace frontend\listeners;

use ms\loyalty\catalog\frontend\forms\CatalogOrderForm;
use Yii;
use yii\base\Event;
use modules\profiles\common\models\Profile;

class CreateCatalogOrderFormListener
{
    public static function whenBeforeValidateAmount(Event $event)
    {
        /* @var CatalogOrderForm $sender */
        /* @var Profile $profile */

        $sender = $event->sender;
        $profile = $sender->getPrizeRecipient();

        if ($profile->blocked_at || $profile->banned_at) {
            $sender->addError('amount', "Вы не можете тратить бонусные баллы. Учетная запись заблокирована. "
                . (empty($profile->blocked_reason) ? $profile->blocked_reason : $profile->blocked_reason));
        }
    }
}