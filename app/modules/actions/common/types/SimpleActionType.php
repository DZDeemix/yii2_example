<?php

namespace modules\actions\common\types;

use modules\actions\common\calculators\BonusesFormulaCalculator;

class SimpleActionType implements ActionTypeInterface
{
    use ActionTypeTrait;

    public function title()
    {
        return "Простая акция";
    }

    public function shortDescription()
    {
        return "Простая акция";
    }

    public function validate()
    {
        return true;
    }

    public function calculateBonuses()
    {
        $calculator = new BonusesFormulaCalculator($this->sale->action);
        $bonuses =$calculator->calculate($this->sale->bonuses);

        return $bonuses;
    }

}