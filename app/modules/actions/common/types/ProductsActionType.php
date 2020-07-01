<?php

namespace modules\actions\common\types;

use modules\actions\common\calculators\BonusesFormulaCalculator;
use yii\helpers\ArrayHelper;

class ProductsActionType implements ActionTypeInterface
{
    use ActionTypeTrait;

    public function title()
    {
        return "Бонус за продажу/закупку определенных позиций";
    }

    public function shortDescription()
    {
        return "Продавец или покупатель получает бонусные баллы за продажу или закупку определенных позиций товаров";
    }

    public function validate()
    {
        if (empty($this->sale->action->products)) {
            return true;
        }

        $saleProductIds = ArrayHelper::map($this->sale->positions, 'product_id', 'product_id');

        foreach ($this->sale->action->products as $actionProduct) {
            if (isset($saleProductIds[$actionProduct->id])) {
                return true;
            }
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