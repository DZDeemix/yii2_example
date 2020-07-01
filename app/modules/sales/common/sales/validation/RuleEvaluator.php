<?php

namespace modules\sales\common\sales\validation;
use Carbon\Carbon;
use modules\sales\common\models\Sale;
use modules\sales\common\models\SaleDocument;
use modules\sales\common\models\SalePosition;
use modules\sales\common\models\SaleValidationRule;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;


/**
 * Class RuleEvaluator
 */
class RuleEvaluator extends BaseObject
{
    /**
     * @var SaleValidationRule
     */
    private $rule;

    public function __construct(SaleValidationRule $rule, $config = [])
    {
        $this->rule = $rule;
        parent::__construct($config);
    }

    /**
     * @param Sale $sale
     * @param SalePosition[] $positions
     * @param SaleDocument[] $documents
     * @return bool
     */
    public function evaluate(Sale $sale, $positions = [], $documents = [])
    {
        $objSale = new \stdClass();
        $objSale->sold_date = new Carbon($sale->sold_on);
        $objSale->quantity = array_sum(ArrayHelper::getColumn($positions, 'quantity'));
        $objSale->number_of_sku = array_sum(array_unique(ArrayHelper::getColumn($positions, 'product_id')));

        return (bool)$this->getFormula()->evaluate([
            'sale' => $objSale,
        ]);
    }

    /**
     * @return Formula
     */
    protected function getFormula()
    {
        return (new Formula($this->rule->rule));
    }
}