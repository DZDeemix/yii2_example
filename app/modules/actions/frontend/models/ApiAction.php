<?php

namespace modules\actions\frontend\models;

use modules\actions\common\models\Action;
use modules\sales\common\models\Group;
use modules\sales\common\models\Category;
use modules\sales\common\models\Product;

/**
 * @inheritdoc
 */
class ApiAction extends Action
{
    /**
     * @return array
     */
    public function fields()
    {
        return array_merge(parent::fields(), [
            'groups' => function (ApiAction $model) {
                return $model->getActualActionGroups()->all();
            },
            'categories' => function (ApiAction $model) {
                return $model->getActualActionCategories()->all();
            },
            'products' => function (ApiAction $model) {
                return $model->getActualActionProducts()->all();
            },
        ]);
    }

    /**
     * Returns actual for action brand groups
     * @return \yii\db\ActiveQuery
     */
    public function getActualActionGroups()
    {
        $actionGroupIds = $this->getActionGroupIds();
        $actualActionProductIds = $this->getActualActionProducts()->select('id')->column();

        $query = Group::find()
            ->orderBy(['{{%sales_groups}}.name' => SORT_ASC]);

        if (empty($actualActionProductIds)) {
            return $query->andWhere('1=0');
        }

        $query
            ->joinWith('products', false)
            ->andWhere(['IN', '{{%sales_products}}.id', $actualActionProductIds])
            ->distinct('{{%sales_groups}}.id');

        if ($actionGroupIds) {
            $query->where(['IN', '{{%sales_groups}}.id', $actionGroupIds]);
        }

        return $query;
    }

    /**
     * Returns actual for action categories
     * @return \yii\db\ActiveQuery
     */
    public function getActualActionCategories()
    {
        $actionCategoryIds = $this->getActionCategoryIds();
        $actualActionProductIds = $this->getActualActionProducts()->select('id')->column();

        $query = Category::find()
            ->orderBy(['{{%sales_categories}}.name' => SORT_ASC]);

        if (empty($actualActionProductIds)) {
            return $query->andWhere('1=0');
        }

        $query
            ->joinWith('products', false)
            ->andWhere(['IN', '{{%sales_products}}.id', $actualActionProductIds])
            ->distinct('{{%sales_categories}}.id');

        if ($actionCategoryIds) {
            $query->andWhere(['IN', '{{%sales_categories}}.id', $actionCategoryIds]);
        }

        return $query;
    }

    /**
     * Returns actual for action product
     * @return \yii\db\ActiveQuery
     */
    public function getActualActionProducts()
    {
        $actionGroupIds = $this->getActionGroupIds();
        $actionCategoriesIds = $this->getActionCategoryIds();
        $actionProductIds = $this->getActionProductIds();

        $query = Product::find()
            ->andWhere(['{{%sales_products}}.enabled' => true])
            ->orderBy(['{{%sales_products}}.title' => SORT_ASC]);

        if ($this->has_products) {
           // $query->andWhere(['{{%sales_products}}.is_show_in_catalog' => true]);
        } else {
            $dummyCategory = Category::findOne(['name' => Category::DUMMY_PLAN_CATEGORY_NAME]);
            $actionCategoriesIds[] = $dummyCategory->id;
        }

        if ($actionGroupIds) {
            $query->andWhere(['IN', 'group_id', $actionGroupIds]);
        }

        if ($actionCategoriesIds) {
            $query->andWhere(['IN', 'category_id', $actionCategoriesIds]);
        }

        if ($actionProductIds) {
            $query->andWhere(['IN', 'id', $actionProductIds]);
        }

        return $query;
    }
}