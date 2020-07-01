<?php

namespace modules\actions\common\types;

use modules\actions\common\calculators\BonusesFormulaCalculator;

class PlanCompleteActionType implements ActionTypeInterface
{
    use ActionTypeTrait;

    public function title()
    {
        return "Бонус за выполнение плана";
    }

    public function shortDescription()
    {
        return "Продавец или покупатель получает бонусные баллы за выполнение поставленного плана";
    }

    /**
     * NOTICE
     * Bonuses for a this action type is created by CronJob from approved sales
     * @see \modules\sales\common\commands\CreatePlanBonusesCommand
     *
     * @return bool
     */
    public function validate()
    {
        return false;
    }
    public function calculateBonuses()
    {
        $calculator = new BonusesFormulaCalculator($this->sale->action);
        $bonuses =$calculator->calculate($this->sale->bonuses);

        return $bonuses;
    }
}