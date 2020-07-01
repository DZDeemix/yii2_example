<?php
defined('YII_ENV') || define('YII_ENV', 'test');
defined('YII_DEBUG') || define('YII_DEBUG', true);

define('YZ_BASE_DIR',  __DIR__ . '/../../..');
define('YZ_APP_DIR', YZ_BASE_DIR . '/app');
define('YZ_VENDOR_DIR', YZ_BASE_DIR . '/app/vendor');

require(YZ_VENDOR_DIR . '/autoload.php');
require(YZ_APP_DIR . '/common/config/env.php');

require(YZ_VENDOR_DIR . '/yiisoft/yii2/Yii.php');
require(YZ_APP_DIR . '/common/config/bootstrap.php');

