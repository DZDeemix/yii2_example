<?php

namespace modules\sales\backend\models;

use modules\sales\common\models\Sale;
use modules\profiles\common\models\Leader;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yz\admin\behaviors\DateRangeFilteringBehavior;
use yz\admin\search\SearchModelEvent;
use yz\admin\search\SearchModelInterface;

/**
 * SaleSearch represents the model behind the search form about `modules\sales\common\models\Sale`.
 */
class SaleSearch extends Sale implements SearchModelInterface
{
    public $totalCost;
    public $totalCount;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['recipient_id', 'bonuses', 'id', 'total_cost', 'action_id'], 'integer'],
            [['status', 'created_at', 'updated_at', 'sold_on', 'approved_by_admin_at', 'bonuses_paid_at'], 'safe'],
            [['created_at_range', 'updated_at_range', 'sold_on_range', 'number',
                'place','project_id', 'totalCost', 'totalCount'], 'safe'],
        ]);
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => DateRangeFilteringBehavior::class,
                'attributes' => [
                    'created_at' => 'sale.created_at',
                    'updated_at' => 'sale.updated_at',
                    'sold_on' => 'sale.sold_on',
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
        
        $dataProvider->setSort([
            'attributes' => [
                'totalCost',
                'totalCount',
            ]
        ]);
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
    protected function prepareQuery()
    {
        $query = Sale::find();
        $query->from(['sale' => self::tableName()]);
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
            'sale.id' => $this->id,
            'sale.recipient_id' => $this->recipient_id,
            'sale.bonuses' => $this->bonuses,
            'sale.total_cost' => $this->total_cost,
            'sale.action_id' => $this->action_id,
        ]);
        $query->andFilterWhere(['like', 'sale.address', $this->address]);
        $query->andFilterWhere(['like', 'sale.status', $this->status]);
        $query->andFilterWhere(['like', 'sale.place', $this->place]);
        $query->andFilterWhere(['like', 'sale.number', $this->number]);
        //$query->andFilterWhere(['like', 'sale.sale_products', empty($this->sale_products) ? null : ':' . $this->sale_products . ':']);
        $query->andFilterWhere(['like', 'sale.sale_groups', empty($this->sale_groups) ? null : ':' . $this->sale_groups . ':']);
        $query->andFilterWhere(['sale.number' => $this->number]);

    }
}
