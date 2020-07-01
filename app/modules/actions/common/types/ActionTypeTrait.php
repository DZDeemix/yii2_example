<?php

namespace modules\actions\common\types;

use modules\sales\common\models\Sale;
use modules\sales\common\models\SaleAction;
use yii\base\Exception;

trait ActionTypeTrait
{
    /**
     * @var Sale
     */
    protected $sale;

    /**
     * @param Sale $sale
     */
    public function __construct(Sale $sale = null)
    {
        $this->sale = $sale;
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function verify()
    {
        if (false === $this->validate()) {
            return false;
        }

       // $this->createSaleAction();

        return true;
    }

    /**
     * @return integer
     */
    public function calculateBonuses()
    {
        /*  $calculator = new BonusesFormulaCalculator($this->sale->action);
        $bonuses = $calculator->calculate($this->sale->bonuses);
        */
        return $this->sale->bonuses;
    }


}