<?php
return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/main.php'),
    [
        'id' => 'app-tests',
        'basePath' => dirname(__DIR__),
        'components' => [
            'db' => [
                'dsn' => ($_ENV['DB_DSN'] ?? null) . '_test',
            ],
        ]
    ]
);