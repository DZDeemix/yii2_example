<?php


namespace modules\burnpoints\console\controllers;


use modules\burnpoints\common\commands\WarningCommand;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\mutex\FileMutex;

class WarningController extends Controller
{
    /**
     * @return int
     * @throws \Exception
     */
    public function actionIndex()
    {
        $mutex = new FileMutex();
        if ($mutex->acquire(__METHOD__) === false) {
            $this->stdout("Another instance is running...\n");
            return ExitCode::OK;
        }

        (new WarningCommand())->handle();

        $mutex->release(__METHOD__);
        return ExitCode::OK;
    }
}