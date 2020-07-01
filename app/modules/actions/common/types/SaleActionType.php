<?php

namespace modules\actions\common\types;

use modules\actions\common\calculators\BonusesFormulaCalculator;

class SaleActionType implements ActionTypeInterface
{
    use ActionTypeTrait;

    public function title()
    {
        return "Бонус за продажу/покупку";
    }

    public function shortDescription()
    {
        return "Продавец или покупатель получает бонусные баллы за продажу или покупку";
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