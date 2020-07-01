<?php

namespace modules\actions\backend\models;

use modules\actions\common\models\ActionProfileByDealer;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yz\admin\search\SearchModelEvent;
use yz\admin\search\SearchModelInterface;
use modules\actions\common\models\ActionProfile;

/**
 * ActionProfileSearch represents the model behind the search form about `modules\actions\common\models\ActionProfileByDealer`.
 */
class ActionProfileByDealerSearch extends ActionProfileByDealer implements SearchModelInterface

{
    /** @var  string */
    public $profileFullname;

    /** @var  string */
    public $phoneMobile;

    /** @var  string */
    public $role;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'action_id'], 'integer'],
            [['created_at', 'updated_at', 'profileFullname', 'phoneMobile', 'role'], 'safe'],
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
        $query =  ActionProfileByDealer::find()
            //->joinWith('profile')
            ->joinWith('action');

        if(\Yii::$app->request->get('action')){
            $query->where(['action_id' => \Yii::$app->request->get('action')]);
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
            'profileFullname' => [
                'asc' => ['{{%profiles}}.full_name' => SORT_ASC],
                'desc' => ['{{%profiles}}.full_name' => SORT_DESC]
            ],
            'phoneMobile' => [
                'asc' => ['{{%profiles}}.phone_mobile' => SORT_ASC],
                'desc' => ['{{%profiles}}.phone_mobile' => SORT_DESC]
            ],
            'role' => [
                'asc' => ['{{%profiles}}.role' => SORT_ASC],
                'desc' => ['{{%profiles}}.role' => SORT_DESC]
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
            'id' => $this->id,
            'action_id' => $this->action_id,
            //'profile_id' => $this->profile_id,
            'last_year_plan' => $this->last_year_plan,
        ]);

        $query->andFilterWhere(['like', '{{%profiles}}.full_name', $this->profileFullname]);
        $query->andFilterWhere(['like', '{{%profiles}}.phone_mobile', $this->phoneMobile]);
        $query->andFilterWhere(['like', '{{%profiles}}.role', $this->role]);


    }
}
