<?php

namespace modules\projects\console\controllers;

use modules\projects\common\commands\ProjectProfileMultipurseCommand;
use yii\console\Controller;
use yii\mutex\FileMutex;

class InitController extends Controller
{
    public function actionMultipurse()
    {
        $mutex = new FileMutex();
        if ($mutex->acquire(__METHOD__) === false) {
            $this->stdout("Another instance is running...\n");
            return self::EXIT_CODE_NORMAL;
        }

        $command = new ProjectProfileMultipurseCommand();
        $command->handle();

        $mutex->release(__METHOD__);
        return self::EXIT_CODE_NORMAL;
    }
}