<?php

namespace modules\sales;
use modules\sales\common\Module;


/**
 * Trait SalesModuleTrait
 */
trait SalesModuleTrait
{
    /**
     * @return Module
     */
    protected static function getSalesModule()
    {
        return \Yii::$app->getModule('sales');
    }
}