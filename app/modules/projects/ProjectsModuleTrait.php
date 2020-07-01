<?php

namespace modules\projects;

use modules\projects\common\Module;

/**
 * Trait SalesModuleTrait
 */
trait ProjectsModuleTrait
{
    /**
     * @return Module
     */
    protected static function getProjectsModule()
    {
        return \Yii::$app->getModule('projects');
    }
}
