<?php

namespace modules\projects\backend\models;

use modules\projects\common\exceptions\MissingProjectException;
use modules\projects\common\models\Project;
use ms\loyalty\catalog\backend\models\CardItemSearch;
use ms\loyalty\catalog\common\cards\CardItem;
use ms\loyalty\catalog\common\models\OrderedCard;
use ms\loyalty\catalog\common\models\ZakazpodarkaOrder;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * @inheritDoc
 * @property integer $project_id
 */
class ProjectCardItemSearch extends CardItemSearch
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['project_id', 'integer'],
        ]);
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
        $query = CardItem::find()->from(['cardItem' => CardItem::tableName()]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['orderedCard' => function (ActiveQuery $query) {
            $query->from(['orderedCard' => OrderedCard::tableName()]);
            $query->joinWith(['zakazpodarkaOrder' => function (ActiveQuery $query) {
                $query->from(['zakazpodarkaOrder' => ZakazpodarkaOrder::tableName()]);
            }]);
        }]);

        $dataProvider->sort->attributes['orderedCard.nominal'] = [
            'asc' => ['orderedCard.nominal' => SORT_ASC],
            'desc' => ['orderedCard.nominal' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['orderedCard.zakazpodarkaOrder.zp_order_id'] = [
            'asc' => ['zakazpodarkaOrder.zp_order_id' => SORT_ASC],
            'desc' => ['zakazpodarkaOrder.zp_order_id' => SORT_DESC],
        ];

        $dataProvider->sort->defaultOrder = [
            'created_at' => SORT_DESC,
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'cardItem.project_id' => $this->project_id,
            'cardItem.push_sent' => $this->push_sent,
            'cardItem.is_required_to_send' => $this->is_required_to_send,
            'cardItem.id' => $this->id,
            'ordered_card_id' => $this->ordered_card_id ? explode(',', $this->ordered_card_id) : null,
            'orderedCard.nominal' => $this->getAttribute('orderedCard.nominal'),
            'zakazpodarkaOrder.zp_order_id' => $this->getAttribute('orderedCard.zakazpodarkaOrder.zp_order_id'),
        ]);

        $query->andFilterWhere(['like', 'orderedCard.type', $this->type])
            ->andFilterWhere(['like', 'item_raw', $this->item_raw]);

        return $dataProvider;
    }
}
