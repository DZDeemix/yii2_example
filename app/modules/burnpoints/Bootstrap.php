<?php

namespace modules\burnpoints;

use marketingsolutions\finance\models\Transaction;
use modules\burnpoints\common\events\TransactionModelEvent;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;

/**
 * Class Bootstrap
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     * @throws \yii\base\ExitException
     */
    public function bootstrap($app)
    {
        Event::on(
            Transaction::class,
            Transaction::EVENT_BEFORE_INSERT,
            [TransactionModelEvent::class, 'beforeSaveMethod']
        );
    }
    
}

