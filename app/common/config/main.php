<?php

use modules\profiles\common\mailing\ProfileMailingList;
use ms\loyalty\location\common\Module;
use yz\admin\mailer\servises\unisender\Unisender;

return [
    'id' => 'yz2-app-standard',
    'language' => 'ru',
    'sourceLanguage' => 'ru-RU',
    'timeZone' => 'Europe/Moscow',
    'extensions' => require(YZ_VENDOR_DIR . '/yiisoft/extensions.php'),
    'vendorPath' => YZ_VENDOR_DIR,
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'bootstrap' => [
        'log',
        \modules\projects\Bootstrap::class,
        \modules\burnpoints\Bootstrap::class,
    ],
    'components' => [
        'db' => [
            'class' => yii\db\Connection::class,
            'charset' => 'utf8',
            'dsn' => $_ENV['DB_DSN'] ?? null,
            'username' => $_ENV['DB_USER'] ?? null,
            'password' => $_ENV['DB_PASSWORD'] ?? null,
            'tablePrefix' => $_ENV['DB_TABLE_PREFIX'] ?? null,
            'attributes' => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));",
            ],
        ],
        'formatter' => [
            'class' => \yii\i18n\Formatter::class,
            'timeZone' => 'Europe/Moscow',
            'defaultTimeZone' => 'Europe/Moscow',
            'dateFormat' => 'dd.MM.yyyy',
            'timeFormat' => 'HH:mm:ss',
            'datetimeFormat' => 'dd.MM.yyyy HH:mm',
        ],
        'i18n' => [
            'translations' => [
                'loyalty' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'ru-RU',
                ],
                'common' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en-US',
                ],
                'frontend' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@frontend/messages',
                    'sourceLanguage' => 'en-US',
                ],
                'backend' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@backend/messages',
                    'sourceLanguage' => 'en-US',
                ],
                'console' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@console/messages',
                    'sourceLanguage' => 'en-US',
                ],
            ]
        ],
        'taxesManager' => [
            'class' => \ms\loyalty\taxes\common\components\TaxesManager::class,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => yii\log\FileTarget::class,
                    'levels' => ['error', 'warning', 'trace'],
                ],
            ],
        ],
        'mailer' => (getenv('MAIL_PROVIDER') == "unisender") ? [
            'class' => Unisender::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'messageConfig' => [
                'from' => ['estimapmk@yandex.ru' => 'estima'],
            ],
            'transport' => [
            ]
        ] : [
            'class' => \yii\swiftmailer\Mailer::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'messageConfig' => [
                'from' => ['estimapmk@yandex.ru' => 'Promo Online'],
            ],
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'estimapmk@yandex.ru',
                'password' => 'Whj439vTjbd88',
                'port' => '465',
                'encryption' => 'SSL',
            ],
        ],
        'mailingMailer' => (getenv('MAIL_PROVIDER') == "unisender") ? [
            'class' => Unisender::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'messageConfig' => [
                'from' => ['estimapmk@yandex.ru' => 'estima'],
            ],
            'transport' => [
            ]
        ] : [
            'class' => \yii\swiftmailer\Mailer::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'messageConfig' => [
                'from' => ['estimapmk@yandex.ru' => 'Promo Online'],
            ],
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'estimapmk@yandex.ru',
                'password' => 'Whj439vTjbd88',
                'port' => '465',
                'encryption' => 'SSL',
            ],
        ],
        'sms' => [
            'class' => \marketingsolutions\sms\Sms::class,
            'services' => [
                'service' => [
                    'class' => \marketingsolutions\sms\services\Smsc::class,
                    'login' => $_ENV['SMSC_LOGIN'] ?? null,
                    'password' => $_ENV['SMSC_PASSWORD'] ?? null,
                    'from' => $_ENV['SMS_FROM'] ?? null,
                ]
            ]
        ],
        'api' => [
            'class' => \ms\loyalty\api\common\components\ApiComponent::class,
            'domain' => $_ENV['API_DOMAIN'] ?? null,
            'headers' => [
                'X-Token' => $_ENV['API_XTOKEN'] ?? null,
            ]
        ],
        'financeChecker' => [
            'class' => \ms\loyalty\finances\common\components\FinanceChecker::class,
            'moneyDifferenceThreshold' => 50000,
            'emailNotificationThreshold' => 100000,
            'email' => 'alex@zakazpodarka.ru',
        ],
        'zp1c' => [
            'class' => \ms\zp1c\client\Client::class,
            'url' => $_ENV['ZP_1C_HOST'] ?? null,
            'login' => $_ENV['ZP_1C_LOGIN'] ?? null,
            'password' => $_ENV['ZP_1C_PASSWORD'] ?? null,
        ],
    ],
    'modules' => [
        'projects' => [
            'class' => modules\projects\common\Module::class,
        ],
        'profiles' => [
            'class' => modules\profiles\common\Module::class,
            'profileType' => modules\profiles\common\Module::PROFILE_TYPE_DEALER_CITY_REGION,
        ],
        'activity' => [
            'class' => modules\activity\common\Module::class,
        ],
        'sales' => [
            'class' => modules\sales\common\Module::class,
        ],
        'actions' => [
            'class' => modules\actions\common\Module::class,
        ],
        'tickets' => [
            'class' => ms\loyalty\tickets\common\Module::class,
            'zmq_port' => 5555,
            'websocket_port' => 8080,
        ],
        'news' => [
            'class' => ms\loyalty\news\common\Module::class,
        ],
        'courses' => [
            'class' => ms\loyalty\courses\common\Module::class,
        ],
        'banners' => [
            'class' => ms\loyalty\banners\common\Module::class,
        ],
        'bonuses' => [
            'class' => modules\bonuses\common\Module::class,
        ],
        'notifications' => [
            'class' => ms\loyalty\notifications\common\Module::class,
        ],
        'mobile' => [
            'class' => ms\loyalty\mobile\common\Module::class,
        ],
        'sms' => [
            'class' => ms\loyalty\sms\common\Module::class,
        ],
        'mailing' => [
            'class' => \yz\admin\mailer\common\Module::class,
            'mailLists' => [
                modules\profiles\common\mailing\ProfileMailingList::class,
            ]
        ],
        'pages' => [
            'class' => ms\loyalty\pages\common\Module::class,
            'layoutGuest' => 'index',
            'layoutUser' => 'main',
        ],
        'feedback' => [
            'class' => ms\loyalty\feedback\common\Module::class,
            'userReplyText' => ' ',
            'userAddText' => 'Мы приняли в работу Ваше обращение и ответим в течение двух рабочих дней. Но постараемся как можно быстрее.',
        ],
        'catalog' => [
            'class' => ms\loyalty\catalog\common\Module::class,
            'disableOrderingForNoTaxAccount' => false,
            'classMap' => [
                'prizeRecipient' => \modules\profiles\common\models\Profile::class,
            ],
            'loyaltyName' => $_ENV['LOYALTY_1C_NAME'] ?? null,
        ],
        'payments' => [
            'class' => ms\loyalty\prizes\payments\common\Module::class,
            'disablePaymentsForNoTaxAccounts' => false,
            'loyaltyName' => $_ENV['LOYALTY_1C_NAME'] ?? null,
        ],
        'shop' => [
            'class' => ms\loyalty\shop\common\Module::class,
            'adminEmails' => ['dk@msforyou.ru'],
        ],
        'taxes' => [
            'class' => ms\loyalty\taxes\common\Module::class,
            'incomeTaxPaymentMethod' => \ms\loyalty\taxes\common\Module::INCOME_TAX_PAYMENT_METHOD_COMPANY,
        ],
        'survey' => [
            'class' => ms\loyalty\survey\common\Module::class,
        ],
        'location' => [
            'class' => ms\loyalty\location\common\Module::class,
        ],
        'checker' => [
            'class' => ms\loyalty\checker\common\Module::class,
            'emails' => '7binary@bk.ru'
        ],
        'api' => [
            'class' => ms\loyalty\api\common\Module::class,
            'authType' => \ms\loyalty\api\common\Module::AUTH_TOKEN,
            'jwtEnabled' => ($_ENV['YII_ENV'] ?? null) === 'prod',
        ],
    ],
    'params' => [
    ],
];
