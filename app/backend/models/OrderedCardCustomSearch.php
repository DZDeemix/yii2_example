<?php

namespace backend\models;

use modules\profiles\common\models\Dealer;
use ms\loyalty\catalog\backend\models\OrderedCardWithProfileSearch;
use yz\admin\search\WithExtraColumns;

class OrderedCardCustomSearch extends OrderedCardWithProfileSearch
{
    use WithExtraColumns;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [self::extraColumns(), 'safe']
        ]);
    }

    /**
     * @inheritDoc
     */
    public function formName()
    {
        return 'OrderedCardCustomSearch';
    }

    protected static function extraColumns()
    {
        return [
            'dealer__name',
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), self::extraColumns());
    }

    protected function getQuery()
    {
        return static::find();
    }

    protected function prepareQuery()
    {
        $query = parent::prepareQuery();

        $query
            ->leftJoin(['dealer' => Dealer::tableName()], 'dealer.id = profile.dealer_id');

        return $query;
    }
}