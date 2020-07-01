<?php


namespace modules\burnpoints\console\controllers;


use modules\burnpoints\common\commands\NullifyCommand;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\mutex\FileMutex;

class NullifyController extends Controller
{
    public function actionIndex()
    {
        $mutex = new FileMutex();
        if ($mutex->acquire(__METHOD__) === false) {
            $this->stdout("Another instance is running...\n");
            return ExitCode::OK;
        }

        (\Yii::createObject(NullifyCommand::class))->handle();

        $mutex->release(__METHOD__);
        return ExitCode::OK;
    }
}
