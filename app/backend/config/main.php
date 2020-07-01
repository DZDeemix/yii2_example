<?php

use modules\activity\common\Module;

return [
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'components' => [
        'request' => [
            'cookieValidationKey' => $_ENV['BACKEND_COOKIE_VALIDATION_KEY'] ?? null,
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'cookieParams' => ['lifetime' => 30 * 24 *60 * 60], # авторизация в админке на 30 дней
            'name' => 'MSSESSIDBACK',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'admin/main/index',
                'login' => 'admin/main/login',
                'logout' => 'admin/main/logout',
                'profile' => 'admin/profile/index',
            ],
        ],
        'user' => [
            'identityClass' => '\yz\admin\models\User',
            'enableAutoLogin' => false,
            'loginUrl' => ['admin/main/login'],
            'on afterLogin' => ['\yz\admin\models\User', 'onAfterLoginHandler'],
        ],
        'authManager' => [
            'class' => yz\admin\components\AuthManager::class,
        ],
        'errorHandler' => [
            'errorAction' => 'admin/main/error',
        ],
    ],
    'modules' => [
        'projects' => [
            'class' => modules\projects\backend\Module::class,
        ],
        'burnpoints' => [
            'class' => modules\burnpoints\backend\Module::class,
        ],
        'admin' => [
            'class' => yz\admin\Module::class,
        ],
        'files-attachments' => [
            'class' => ms\files\attachments\common\Module::class,
        ],
        'profiles' => [
            'class' => modules\profiles\backend\Module::class,
        ],
        'activity' => [
            'class' => modules\activity\backend\Module::class,
        ],
        'sales' => [
            'class' => modules\sales\backend\Module::class,
        ],
        'actions' => [
            'class' => modules\actions\backend\Module::class,
        ],
        'manual' => [
            'class' => ms\loyalty\bonuses\manual\backend\Module::class,
        ],
        'tickets' => [
            'class' => ms\loyalty\tickets\backend\Module::class,
        ],
        'courses' => [
            'class' => ms\loyalty\courses\backend\Module::class,
        ],
        'news' => [
            'class' => ms\loyalty\news\backend\Module::class,
        ],
        'banners' => [
            'class' => ms\loyalty\banners\backend\Module::class,
        ],
        'notifications' => [
            'class' => ms\loyalty\notifications\backend\Module::class,
        ],
        'location' => [
            'class' => ms\loyalty\location\backend\Module::class,
        ],
        'pages' => [
            'class' => ms\loyalty\pages\backend\Module::class,
        ],
        'filemanager' => [
            'class' => yz\admin\elfinder\Module::class,
            'roots' => [
                [
                    'baseUrl' => '@frontendWeb',
                    'basePath' => '@frontendWebroot',
                    'path' => 'media/uploads',
                    'name' => 'Файлы на сайте',
                ]
            ]
        ],
        'catalog' => [
            'class' => ms\loyalty\catalog\backend\Module::class,
        ],
        'payments' => [
            'class' => ms\loyalty\prizes\payments\backend\Module::class,
        ],
        'shop' => [
            'class' => ms\loyalty\shop\backend\Module::class,
        ],
        'feedback' => [
            'class' => ms\loyalty\feedback\backend\Module::class,
        ],
        'survey' => [
            'class' => ms\loyalty\survey\backend\Module::class,
        ],
        'finances' => [
            'class' => ms\loyalty\finances\backend\Module::class,
        ],
        'mobile' => [
            'class' => ms\loyalty\mobile\backend\Module::class,
        ],
        'sms' => [
            'class' => ms\loyalty\sms\backend\Module::class,
        ],
        'mailing' => [
            'class' => yz\admin\mailer\backend\Module::class,
        ],
        'taxes' => [
            'class' => ms\loyalty\taxes\backend\Module::class,
        ],
        'reports' => [
            'class' => ms\loyalty\reports\backend\Module::class,
            'reports' => [
                modules\profiles\backend\reports\ProfilesStat::class,
                ms\loyalty\feedback\backend\reports\FeedbackStat::class,
                modules\profiles\backend\reports\NDFLReport::class,
            ]
        ],
        'api' => [
            'class' => ms\loyalty\api\backend\Module::class,
        ],
        'checker' => [
            'class' => ms\loyalty\checker\backend\Module::class,
        ],

        'rsbcards' => [
            'class' => \ms\loyalty\rsb\cards\common\Module::class,
            'loyaltyName' => getenv('LOYALTY_1C_NAME'),
        ],
    ],
    'params' => [

    ],
];


