<?php


namespace modules\burnpoints\console\controllers;


use modules\burnpoints\common\commands\CalculateCommand;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\mutex\FileMutex;

class CalculateController extends Controller
{
    public function actionIndex()
    {
        $mutex = new FileMutex();
        if ($mutex->acquire(__METHOD__) === false) {
            $this->stdout("Another instance is running...\n");
            return ExitCode::OK;
        }
        
        (new CalculateCommand())->handle();
        
        $mutex->release(__METHOD__);
        return ExitCode::OK;
    }
}
