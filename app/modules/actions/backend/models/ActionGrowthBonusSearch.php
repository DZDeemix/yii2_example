<?php

namespace modules\actions\backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yz\admin\search\SearchModelEvent;
use yz\admin\search\SearchModelInterface;
use modules\actions\common\models\ActionGrowthBonus;

/**
 * ActionGrowthBonusSearch represents the model behind the search form about `modules\actions\common\models\ActionGrowthBonus`.
 */
class ActionGrowthBonusSearch extends ActionGrowthBonus implements SearchModelInterface

{

    /** @var  string */
    public $actionTitle;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'action_id', 'bonus', 'growth_from', 'growth_to'], 'integer'],
            ['actionTitle', 'safe'],
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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

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
        $query =  ActionGrowthBonus::find()
            ->joinWith('action');
        if(Yii::$app->request->get('action_id')){
            $query->where(['action_id' => Yii::$app->request->get('action_id')]);
        }
        return $query;
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
        $sort = $dataProvider->getSort();
        $sort->attributes = array_merge($sort->attributes, [
            'actionTitle' => [
                'asc' => ['{{%actions}}.title' => SORT_ASC],
                'desc' => ['{{%actions}}.title' => SORT_DESC]
            ],
        ]);
        $dataProvider->setSort($sort);
        return $dataProvider;
    }

    /**
     * @param ActiveQuery $query
     */
    protected function prepareFilters($query)
    {
        $query->andFilterWhere([
            'action_id' => $this->action_id,
            'growth_from' => $this->growth_from,
            'growth_to' => $this->growth_to,
            'bonus' => $this->bonus,
        ]);

        $query->andFilterWhere(['like', '{{%actions}}.title', $this->actionTitle]);

    }
}
