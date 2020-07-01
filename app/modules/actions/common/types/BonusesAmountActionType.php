<?php

namespace modules\actions\common\types;

use modules\actions\common\calculators\BonusesFormulaCalculator;

class BonusesAmountActionType implements ActionTypeInterface
{
    use ActionTypeTrait;

    public function title()
    {
        return "Бонус за разовую продажу/закупку на определенную сумму";
    }

    public function shortDescription()
    {
        return "Продавец или покупатель получает бонусные баллы за разовую закупку на определенную сумму";
    }

    public function validate()
    {
        $saleBonuses = $this->sale->bonuses;
        $actionThresholdBonuses = $this->sale->action->bonuses_amount;

        if ($saleBonuses >= $actionThresholdBonuses) {
            return true;
        }

        return false;
    }

    public function calculateBonuses()
    {
        $calculator = new BonusesFormulaCalculator($this->sale->action);
        $bonuses =$calculator->calculate($this->sale->bonuses);

        return $bonuses;
    }
}