<?php

namespace modules\sales\frontend\widgets;

use modules\sales\common\models\Category;
use modules\sales\common\models\Group;
use modules\sales\common\models\Product;
use modules\sales\common\models\Sale;
use modules\sales\SalesModuleTrait;
use Yii;
use yii\base\Widget;
use yii\db\Query;

/**
 * Class EditSaleWidget
 */
class EditSaleWidget extends Widget
{
    /** @var Sale */
    public $sale;

    public $backend = false;

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

        return $this->render('edit-sale', [
            'products' => $products,
            'sale' => $this->sale,
            'backend' => $this->backend,
        ]);
    }
}