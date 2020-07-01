<?php

namespace modules\sales\frontend\widgets;

use modules\sales\SalesModuleTrait;
use yii\base\Widget;


/**
 * Class SalesDashboard
 */
class SalesDashboard extends Widget
{
    use SalesModuleTrait;

    public function run()
    {
        return $this->render('sales-dashboard', [
            'allowNewSales' => self::getSalesModule()->allowNewSalesCreation,
        ]);
    }
}