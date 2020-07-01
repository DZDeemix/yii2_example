<?php

namespace modules\sales\backend\rbac;
use yii\rbac\Item;


/**
 * Class Rbac
 */
class Rbac
{
    const ROLE_RECEIVE_ACTIVATIONS_NOTIFICATIONS = 'ROLE_RECEIVE_ACTIVATIONS_NOTIFICATIONS';

    public static function dependencies()
    {
        return [
            self::ROLE_RECEIVE_ACTIVATIONS_NOTIFICATIONS => ['Получает уведомления о попытках активации серийных номеров', Item::TYPE_ROLE, []],
        ];
    }
}