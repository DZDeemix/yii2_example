<?php

use ms\loyalty\whatsapp\backend\Module;

return [
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'cookieValidationKey' => $_ENV['FRONTEND_COOKIE_VALIDATION_KEY'] ?? null,
        ],
        'session' => [
            'name' => 'MSSESSIDFRONT',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'user' => [
            'identityClass' => modules\profiles\common\models\Profile::class,
            'loginUrl' => ['/'],
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
    ],
    'modules' => [
        'files-attachments' => [
            'class' => ms\files\attachments\common\Module::class,
        ],
        'api' => [
            'class' => ms\loyalty\api\frontend\Module::class,
        ],
        'profiles' => [
            'class' => modules\profiles\frontend\Module::class,
        ],
        'actions' => [
            'class' => modules\actions\frontend\Module::class,
        ],
        'courses' => [
            'class' => ms\loyalty\courses\frontend\Module::class,
        ],
        'news' => [
            'class' => ms\loyalty\news\frontend\Module::class,
        ],
        'posters' => [
            'class' => ms\loyalty\banners\frontend\Module::class,
        ],
        'notifications' => [
            'class' => ms\loyalty\notifications\frontend\Module::class,
        ],
        'mobile' => [
            'class' => ms\loyalty\mobile\frontend\Module::class,
        ],
        'catalog' => [
            'class' => ms\loyalty\catalog\frontend\Module::class,
        ],
        'payments' => [
            'class' => ms\loyalty\prizes\payments\frontend\Module::class,
        ],
        'tickets' => [
            'class' => ms\loyalty\tickets\frontend\Module::class,
        ],
        'shop' => [
            'class' => ms\loyalty\shop\frontend\Module::class,
        ],
        'survey' => [
            'class' => ms\loyalty\survey\frontend\Module::class,
        ],
        'feedback' => [
            'class' => ms\loyalty\feedback\frontend\Module::class,
        ],
        'taxes' => [
            'class' => ms\loyalty\taxes\frontend\Module::class,
        ],
        'pages' => [
            'class' => ms\loyalty\pages\frontend\Module::class,
        ],
        'location' => [
            'class' => ms\loyalty\location\frontend\Module::class,
        ],
        'sales' => [
            'class' => modules\sales\frontend\Module::class,
        ],
    ],
    'params' => [
        'defaultTitle' => 'Мотивационная программа',
    ],
];
