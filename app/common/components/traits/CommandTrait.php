<?php

namespace common\components\traits;

use Yii;
use yii\helpers\Console;
use yii\mutex\FileMutex;
use common\components\interfaces\CommandInterface;

trait CommandTrait
{
    /**
     * @param CommandInterface $command
     * @return bool
     */
    public function runInstance(CommandInterface $command)
    {
        $mutex = new FileMutex();
        $commandClass = get_class($command);

        if (false === $mutex->acquire($commandClass)) {
            $this->consoleOutput("Another instance is running...");

            return false;
        }

        $result = $command->handle();

        $mutex->release($commandClass);

        return $result;
    }

    /**
     * @param string $message
     * @return bool|int
     */
    public function consoleOutput(string $message)
    {
        if (Yii::$app->request->isConsoleRequest) {
            return Console::output($message);
        }

        return false;
    }
}
