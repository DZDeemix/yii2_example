<?php

namespace modules\sales\backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yz\admin\behaviors\DateRangeFilteringBehavior;
use yz\admin\search\SearchModelEvent;
use yz\admin\search\SearchModelInterface;
use modules\sales\common\models\SalePosition;

/**
 * SalePositionSearch represents the model behind the search form about `modules\sales\common\models\SalePosition`.
 */
class SalePositionSearch extends SalePosition implements SearchModelInterface

{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'sale_id', 'product_id', 'quantity', 'bonuses', 'cost'], 'integer'],
            [['sale__created_at_range', 'sale__sold_on_range', 'sale__total_cost'], 'safe'],
        ]);
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => DateRangeFilteringBehavior::class,
                'attributes' => [
                    'sale__created_at' => 'sale.created_at',
                    'sale__sold_on' => 'sale.sold_on',
                ]
            ]
        ]);
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
        $query = $this->prepareQuery();
        $this->trigger(self::EVENT_AFTER_PREPARE_QUERY, new SearchModelEvent([
            'query' => $query,
        ]));

        $dataProvider = $this->prepareDataProvider($query);
        $this->trigger(self::EVENT_AFTER_PREPARE_DATA_PROVIDER, new SearchModelEvent([
            'query' => $query,
            'dataProvider' => $dataProvider,
        ]));

        $this->load($params);

        $this->prepareFilters($query);
        $this->trigger(self::EVENT_AFTER_PREPARE_FILTERS, new SearchModelEvent([
            'query' => $query,
            'dataProvider' => $dataProvider,
        ]));

        return $dataProvider;
    }

    /**
     * @return ActiveQuery
     */
    protected function getQuery()
    {
        return SalePosition::find();
    }

    /**
     * @return ActiveQuery
     */
    protected function prepareQuery()
    {
        $query = $this->getQuery();
        return $query;
    }

    /**
     * @param ActiveQuery $query
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider($query)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }

    /**
     * @param ActiveQuery $query
     */
    protected function prepareFilters($query)
    {
        $query->andFilterWhere([
            'sp.id' => $this->id,
            'sp.sale_id' => $this->sale_id,
            'sp.product_id' => $this->product_id,
            'sp.quantity' => $this->quantity,
            'sp.bonuses' => $this->bonuses,
            'sp.cost' => $this->cost,
        ]);
    }
}
