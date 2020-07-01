<?php

namespace modules\actions\common\commands;

use Yii;
use yii\base\Component;
use common\components\interfaces\CommandInterface;
use common\components\traits\CommandTrait;
use modules\actions\common\models\Action;

/**
 * Finishes active actions that has expired
 */
class FinishActionCommand extends Component implements CommandInterface
{
    use CommandTrait;

    public function handle()
    {
        try {
            $transaction = Yii::$app->db->beginTransaction();

            $actions = $this->getActions();

            $this->consoleOutput("Active actions count: " . $actions->count());

            foreach ($actions->each() as $action) {
                /* @var Action $action */
                $action->statusManager->finish();

                $this->consoleOutput("#$action->id - $action->title - $action->start_on-$action->end_on - FINISHED");
            }

            $transaction->commit();
            $this->consoleOutput("Success");

            return true;

        } catch (\Throwable $e) {

            $transaction->rollBack();
            $this->consoleOutput("Error\n" . $e->getMessage());

            return false;
        }
    }

    /**
     * @return \modules\actions\common\models\ActionQuery
     * @throws \yii\base\InvalidConfigException
     */
    private function getActions()
    {
        return Action::find()
            ->finished()
            ->active();
    }

}