<?php

namespace modules\sales\frontend\widgets;

use modules\sales\common\models\Category;
use modules\sales\common\models\Group;
use modules\sales\common\models\Product;
use modules\sales\SalesModuleTrait;
use yii\base\Widget;
use yii\db\Query;

/**
 * Class SalesDashboard
 */
class AddSale extends Widget
{
    use SalesModuleTrait;

    public function run()
    {
        $products = (new Query())
            ->select('product.id, product.category_id, product.name, product.bonuses_formula, category.name categoryName, group.name groupName')
            ->from(['product' => Product::tableName()])
            ->leftJoin(['category' => Category::tableName()], 'category.id = product.category_id')
            ->leftJoin(['group' => Group::tableName()], 'group.id = product.group_id')
            ->orderBy(['groupName' => SORT_ASC, 'categoryName' => SORT_ASC])
            ->where(['enabled' => true])
            ->all();

        return $this->render('add-sale', compact('products'));
    }
}