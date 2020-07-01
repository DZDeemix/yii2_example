<?php

namespace modules\projects\backend\models;

use marketingsolutions\finance\models\Purse;
use modules\profiles\backend\models\ProfileTransactionSearch;
use modules\profiles\common\models\Profile;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yz\admin\behaviors\DateRangeFilteringBehavior;
use yz\admin\search\SearchModelEvent;
use yz\admin\search\SearchModelInterface;
use yz\admin\search\WithExtraColumns;

/**
 * Class ProfileTransactionSearch
 */
class ProjectProfileTransactionSearch extends ProfileTransactionSearch implements SearchModelInterface
{
    use WithExtraColumns;

    /**
     * @return ActiveQuery
     */
    public static function find()
    {
        return parent::find()
            ->select(static::selectWithExtraColumns(['transaction.*']))
            ->from(['transaction' => ProjectProfileTransactionSearch::tableName()])
            ->joinWith(['purse' => function (ActiveQuery $query) {
                $query
                    ->from(['purse' => Purse::tableName()]);
            }])
            ->joinWith(['profile' => function (ActiveQuery $query) {
                $query
                    ->from(['profile' => Profile::tableName()]);
            }])
            ->where(['purse.owner_type' => Profile::class]);
    }

    protected static function extraColumns()
    {
        return [
            'purse__balance',
            'purse__owner_id',
            'purse__project_id',
            'profile__full_name',
            'profile__phone_mobile',
            'profile__city_id',
            'profile__role',
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), static::extraColumns());
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'purse__project_id' => 'Проект',
            'purse__balance' => 'Баланс',
            'profile__full_name' => 'Участник',
            'profile__phone_mobile' => 'Номер телефона',
            'profile__city_id' => 'Город',
            'profile__role' => 'Роль',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'purse_id'], 'integer'],
            [['type', 'amount', 'balance_before', 'balance_after', 'title', 'comment'], 'safe'],
            [['created_at_range'], 'safe'],
            [static::extraColumns(), 'safe'],
        ];
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => DateRangeFilteringBehavior::class,
                'attributes' => [
                    'created_at' => 'transaction.created_at',
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
    protected function prepareQuery()
    {
        $query = $this->getQuery();
        return $query;
    }

    /**
     * @return ActiveQuery
     */
    protected function getQuery()
    {
        return ProjectProfileTransactionSearch::find();
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
        $dataProvider->sort->defaultOrder = [
            'created_at' => SORT_DESC,
        ];
        return $dataProvider;
    }

    /**
     * @param ActiveQuery $query
     */
    protected function prepareFilters($query)
    {
        $query->andFilterWhere([
            'transaction.id' => $this->id,
            'transaction.type' => $this->type,
        ]);

        $query->andFilterHaving([
            'purse__project_id' => $this->purse__project_id,
        ]);

        $query->andFilterWhere(['like', 'transaction.title', $this->title]);
        $query->andFilterWhere(['like', 'transaction.comment', $this->comment]);

        static::filtersForExtraColumns($query);
    }
}