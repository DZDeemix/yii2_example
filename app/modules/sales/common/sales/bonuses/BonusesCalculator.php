<?php

namespace modules\sales\common\sales\bonuses;

use modules\sales\common\models\Product;
use yii\base\BaseObject;


/**
 * Class BonusesCalculator
 */
class BonusesCalculator extends BaseObject
{
    /**
     * @var Product
     */
    private $product;

    public function __construct(Product $product, $config = [])
    {
        parent::__construct($config);
        $this->product = $product;
    }


    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Calculates bonuses for given quantity
     * @param integer $quantity
     * @return int
     */
    public function calculateForLocalQuantity($quantity)
    {
        return $this->getFormula()->evaluate([
            'q' => $quantity
        ]);
    }

    /**
     * Calculates bonuses for given quantity
     * @param integer $quantity
     * @return int
     */
    public function calculateForStoredQuantity($quantity)
    {
        return $quantity;
    }

    /**
     * @return Formula
     */
    protected function getFormula()
    {
        return (new Formula($this->product->bonuses_formula));
    }
}