<?php

namespace modules\actions\backend\models;

use modules\actions\common\models\ActionParticipant;
use modules\actions\common\models\ActionParticipantWithData;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yz\admin\search\SearchModelEvent;
use yz\admin\search\SearchModelInterface;

/**
 * ActionParticipantSearch represents the model behind the search form about `modules\actions\common\models\ActionParticipant`.
 */
class ActionParticipantSearch extends ActionParticipant implements SearchModelInterface
{
    public $is_participant = null;
    public $has_sale = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {

    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {

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
        $this->load($params);
        $query = $this->prepareQuery();
        $this->trigger(self::EVENT_AFTER_PREPARE_QUERY, new SearchModelEvent([
            'query' => $query,
        ]));

        $dataProvider = $this->prepareDataProvider($query);
        $this->trigger(self::EVENT_AFTER_PREPARE_DATA_PROVIDER, new SearchModelEvent([
            'query' => $query,
            'dataProvider' => $dataProvider,
        ]));



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
        $query = ActionParticipant::find();

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
        if($this->has_sale==="1"){
            $query->where("sale.id is not null");
        }
        if($this->has_sale==="0"){
            $query->where("sale.id is  null");
        }
    }


}
