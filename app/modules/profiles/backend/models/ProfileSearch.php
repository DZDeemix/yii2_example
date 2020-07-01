<?php

namespace modules\profiles\backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yz\admin\behaviors\DateRangeFilteringBehavior;
use yz\admin\search\SearchModelEvent;
use yz\admin\search\SearchModelInterface;

/**
 * ProfileSearch represents the model behind the search form about `ms\loyalty\profiles\simple\common\models\Profile`.
 */
class ProfileSearch extends ProfileWithData implements SearchModelInterface
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'dealer_id', 'city_id', 'region_id', 'external_id'], 'integer'],
            [['info', 'role', 'specialty', 'uniqid', 'is_uploaded'], 'safe'],
            [['first_name', 'last_name', 'middle_name', 'full_name', 'phone_mobile', 'email', 'created_at', 'updated_at'], 'safe'],
            [['banned', 'banned_at', 'banned_reason', 'blocked', 'blocked_at', 'blocked_reason', 'checked_at','is_checked'], 'safe'],
            [['phone_confirmed_at', 'email_confirmed_at', 'registered_at'], 'safe'],
            [['created_at_range', 'registered_at_range', 'company_name', 'social_link'], 'safe'],
            [static::extraColumns(), 'safe'],
        ];
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => DateRangeFilteringBehavior::class,
                'attributes' => [
                    'created_at' => 'profile.created_at',
                    'registered_at' => 'profile.registered_at',
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
        return ProfileWithData::find();
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
            'profile.id' => $this->id,
            'profile.dealer_id' => $this->dealer_id,
            'profile.role' => $this->role,
            'profile.is_uploaded' => $this->is_uploaded,
        ]);

        $query->andFilterWhere(['like', 'profile.first_name', $this->first_name])
            ->andFilterWhere(['like', 'profile.last_name', $this->last_name])
            ->andFilterWhere(['like', 'profile.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'profile.full_name', $this->full_name])
            ->andFilterWhere(['like', 'profile.phone_mobile', $this->phone_mobile])
            ->andFilterWhere(['like', 'profile.email', $this->email])
            ->andFilterWhere(['like', 'profile.uniqid', $this->uniqid])
            ->andFilterWhere(['like', 'profile.banned_reason', $this->banned_reason])
            ->andFilterWhere(['like', 'profile.blocked_reason', $this->blocked_reason])
            ->andFilterWhere(['like', 'profile.external_id', $this->external_id])
            ->andFilterWhere(['like', 'profile.company_name', $this->company_name])
            ->andFilterWhere(['like', 'profile.social_link', $this->social_link])
            ->andFilterWhere([ 'profile.is_checked'=>$this->is_checked])
            ->andFilterWhere([ 'profile.city_id'=>$this->city_id])

        ;

        static::filtersForExtraColumns($query);
    }
}
