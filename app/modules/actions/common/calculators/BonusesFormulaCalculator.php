<?php

namespace modules\actions\common\calculators;

use modules\actions\common\models\Action;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class BonusesFormulaCalculator
{
    /**
     * @var Action
     */
    private $_action;

    /**
     * @param Action $action
     */
    function __construct(Action $action)
    {
        $this->_action = $action;
    }

    /**
     * @param int $bonuses
     * @return int
     */
    public function calculate(int $bonuses)
    {
       /* $language = new ExpressionLanguage();
        $bonuses = $language->evaluate($this->_action->bonuses_formula, [
            'bonuses' => $bonuses
        ]);*/

        return (int) $bonuses*$this->_action->bonuses_formula;
    }
}