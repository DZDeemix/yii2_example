<?php

namespace modules\sales\backend\models;

use modules\sales\common\models\Category;
use modules\sales\common\models\Product;
use modules\sales\common\models\Unit;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * ProductSearch represents the model behind the search form about `modules\sales\common\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'group_id', 'price', 'weight', 'unit_id'], 'integer'],
            [['name','bonuses_formula', 'name_html', 'code', 'title', 'description', 'url', 'enabled', 'role'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Product::find();
        $query
            ->joinWith(['unit' => function (ActiveQuery $query) {
                $query->from(['unit' => Unit::tableName()]);
            }])
            ->joinWith(['category' => function (ActiveQuery $query) {
                $query->from(['category' => Category::tableName()]);
            }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            '{{%sales_products}}.id' => $this->id,
            '{{%sales_products}}.category_id' => $this->category_id,
            '{{%sales_products}}.group_id' => $this->group_id,
            '{{%sales_products}}.unit_id' => $this->unit_id,
            '{{%sales_products}}.enabled' => $this->enabled,
            '{{%sales_products}}.role' => $this->role,
        ]);

        $query->andFilterWhere(['like', '{{%sales_products}}.name', $this->name])
            ->andFilterWhere(['like', '{{%sales_products}}.bonuses_formula', $this->bonuses_formula]);

        return $dataProvider;
    }
}
