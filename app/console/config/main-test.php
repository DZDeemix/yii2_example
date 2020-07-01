<?php
return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-test.php'),
    require(__DIR__ . '/main.php'),
    [
        'id' => 'app-console-tests',
    ]
);