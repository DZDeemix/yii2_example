<?php

use modules\activity\common\Module;

return [
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'migrate' => [
            'class' => dmstr\console\controllers\MigrateController::class,
            'migrationTable' => '{{%migrations}}',
            'migrationPath' => '@migrations',
        ],
    ],
    'components' => [
        'schedule' => [
            'class' => marketingsolutions\scheduling\Schedule::class,
        ],
    ],
    'modules' => [
        'projects' => [
            'class' => modules\projects\console\Module::class,
        ],
        'burnpoints' => [
            'class' => modules\burnpoints\console\Module::class,
        ],
        'mailing' => [
            'class' => yz\admin\mailer\console\Module::class,
        ],
        'mobile' => [
            'class' => ms\loyalty\mobile\console\Module::class,
        ],
        'api' => [
            'class' => ms\loyalty\api\console\Module::class,
        ],
        'catalog' => [
            'class' => ms\loyalty\catalog\console\Module::class,
        ],
        'payments' => [
            'class' => ms\loyalty\prizes\payments\console\Module::class,
        ],
        'sms' => [
            'class' => ms\loyalty\sms\console\Module::class,
        ],
        'shop' => [
            'class' => ms\loyalty\shop\console\Module::class,
        ],
        'checker' => [
            'class' => ms\loyalty\checker\console\Module::class,
        ],
        'actions' => [
            'class' => modules\actions\console\Module::class,
        ],
        'activity' => [
            'class' => modules\activity\console\Module::class,
        ],
        'tickets' => [
            'class' => ms\loyalty\tickets\console\Module::class,
        ],
        'sales' => [
            'class' => modules\sales\console\Module::class,
        ],
    ],
    'params' => [
        'yii.migrations' => [
            '@modules/profiles/migrations',
            '@modules/actions/migrations',
            '@modules/sales/migrations',
            '@modules/activity/migrations',
            '@modules/projects/migrations',
            '@modules/burnpoints/migrations',
        ],
    ],
];
