<?php

namespace modules\profiles\backend\models;

use modules\profiles\backend\rbac\Rbac;
use modules\profiles\common\models\Leader;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yz\admin\search\SearchModelEvent;
use yz\admin\search\SearchModelInterface;

/**
 * LeaderSearch represents the model behind the search form about `modules\profiles\common\models\Leader`.
 */
class LeaderSearch extends Leader implements SearchModelInterface
{
    public $login;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['role', 'login', 'full_name', 'phone_mobile', 'email'], 'safe'],
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
        $leader = Leader::getLeaderByIdentity();
        $query = self::find();
        if ($leader) {
            /*if ($leader->role == Rbac::ROLE_ADMIN_REGION) {
                $query->andWhere(
                    ['leader_id' => $leader->id]
                );
            };*/
        }
        return $query;
    }

    /**
     * @return ActiveQuery
     */
    protected function prepareQuery()
    {
        $query = $this->getQuery();

        $query->joinWith([
            'adminUser',
        ]);

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
            '{{%leaders}}.id' => $this->id,
        ]);

        $query->andFilterWhere(['LIKE', '{{%leaders}}.role', $this->role]);
        $query->andFilterWhere(['LIKE', '{{%leaders}}.full_name', $this->full_name]);
        $query->andFilterWhere(['LIKE', '{{%leaders}}.phone_mobile', $this->phone_mobile]);
        $query->andFilterWhere(['LIKE', '{{%leaders}}.email', $this->email]);
        $query->andFilterWhere(['LIKE', '{{%admin_users}}.login', $this->login]);
    }
}
