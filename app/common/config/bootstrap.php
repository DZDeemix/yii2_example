<?php

/**
 * Aliases configuration
 *
 * All aliases that are used in application are placed here
 */

Yii::setAlias('base', YZ_BASE_DIR);

// Applications
Yii::setAlias('common', YZ_APP_DIR . '/common');
Yii::setAlias('frontend', YZ_APP_DIR . '/frontend');
Yii::setAlias('backend', YZ_APP_DIR . '/backend');
Yii::setAlias('console', YZ_APP_DIR . '/console');

// Modules
Yii::setAlias('modules', YZ_APP_DIR . '/modules');

// Migrations
Yii::setAlias('migrations', YZ_APP_DIR . '/migrations');

// Data
Yii::setAlias('data',  YZ_BASE_DIR . '/data');

// Web
Yii::setAlias('frontendWebroot', '@frontend/web');
Yii::setAlias('backendWebroot', '@backend/web');
Yii::setAlias('frontendWeb', $_ENV['FRONTEND_WEB'] ?? null);
Yii::setAlias('backendWeb', $_ENV['BACKEND_WEB'] ?? null);
Yii::setAlias('frontendSpa', $_ENV['FRONTEND_SPA'] ?? null);

// App version
if (file_exists(YZ_BASE_DIR.'/.version')) {
    define('APP_VERSION', file_get_contents(YZ_BASE_DIR.'/.version'));
} else {
    define('APP_VERSION', 'latest');
}

Yii::$container->set(
    \ms\loyalty\contracts\profiles\ProfileFinderInterface::class,
    \modules\profiles\common\models\ProfileFinder::class
);

$listener = new \marketingsolutions\events\Listener(
    new \marketingsolutions\events\PatternEventsProvider(),
    new \marketingsolutions\events\PrefixMethodFinder()
);

// Extranet
// \ms\loyalty\extranet\common\listeners\Listener::bootstrap();