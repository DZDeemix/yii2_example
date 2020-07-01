<?php

namespace modules\actions\backend\models;

use modules\profiles\backend\rbac\Rbac;
use modules\profiles\common\models\LeaderAdminRegion;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yz\admin\search\SearchModelEvent;
use yz\admin\search\SearchModelInterface;
use modules\actions\common\models\Action;
use modules\profiles\common\models\Leader;

/**
 * ActionSearch represents the model behind the search form about `modules\mechanics\common\models\Action`.
 */
class ActionSearch extends Action implements SearchModelInterface
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'bonuses_amount', 'plan_amount'], 'integer'],
            [['start_on', 'end_on', 'title', 'short_description', 'description',
                'role', 'pay_type', 'status', 'bonuses_formula', 'code'], 'safe'],
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
     * @param array $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
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
     * @throws \yii\base\InvalidConfigException
     */
    protected function getQuery()
    {
        return  self::find();
    }

    /**
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    protected function prepareQuery()
    {
        $query = $this->getQuery();

        $query->joinWith([
            'regions',
            'cities',
            'dealers',
            'profiles',
            'sales'
        ]);

        $leader = Leader::getLeaderByIdentity();

        if ($leader && $leader->role == Rbac::ROLE_ADMIN_JUNIOR) {
            // Regions filter
            $query->andWhere([
                'OR',
                ['{{%actions_regions}}.region_id' => Leader::getLeaderRegion()],
                ['IS', '{{%actions_regions}}.region_id', new Expression('NULL')],
            ]);

            // Cities filter
            $citiesIds = $leader->roleManager->getCitiesIds();
            $query->andWhere([
                'OR',
                ['IN', '{{%actions_cities}}.city_id', $citiesIds],
                ['IS', '{{%actions_cities}}.city_id', new Expression('NULL')],
            ]);

            // Dealers filter
//            $dealerIds = $leader->roleManager->getDealerIds();
//            $query->andWhere([
//                'OR',
//                ['IN', '{{%actions_dealers}}.dealer_id', $dealerIds],
//                ['IS', '{{%actions_dealers}}.dealer_id', new Expression('NULL')],
//            ]);
//
//            // Profiles filter
//            $profilesIds = $leader->roleManager->getProfileIds();
//            $query->andWhere([
//                'OR',
//                ['IN', '{{%actions_profiles}}.profile_id', $profilesIds],
//                ['IS', '{{%actions_profiles}}.profile_id', new Expression('NULL')],
//            ]);
     }


        $query->groupBy('{{%actions}}.id');

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
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ]
        ]);

        return $dataProvider;
    }

    /**
     * @param ActiveQuery $query
     */
    protected function prepareFilters($query)
    {
        $query->andFilterWhere([
            '{{%actions}}.id' => $this->id,
            '{{%actions}}.type_id' => $this->type_id,
            '{{%actions}}.pay_type' => $this->pay_type,
            '{{%actions}}.role' => $this->role,
            '{{%actions}}.status' => $this->status,
            '{{%actions}}.bonuses_amount' => $this->bonuses_amount,
            '{{%actions}}.plan_amount' => $this->plan_amount,
        ]);

        $query->andFilterWhere(['LIKE', '{{%actions}}.title', $this->title]);
        $query->andFilterWhere(['LIKE', '{{%actions}}.code', $this->code]);
        $query->andFilterWhere(['LIKE', '{{%actions}}.short_description', $this->short_description]);
        $query->andFilterWhere(['LIKE', '{{%actions}}.description', $this->description]);
        $query->andFilterWhere(['LIKE', '{{%actions}}.bonuses_formula', $this->bonuses_formula]);
    }
}
