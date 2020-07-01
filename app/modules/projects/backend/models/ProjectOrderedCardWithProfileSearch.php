<?php

namespace modules\projects\backend\models;

use modules\projects\common\exceptions\MissingProjectException;
use modules\projects\common\models\Project;
use ms\loyalty\catalog\backend\models\OrderedCardWithProfileSearch;

/**
 * @inheritDoc
 * @property integer $project_id
 */
class ProjectOrderedCardWithProfileSearch extends OrderedCardWithProfileSearch
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['project_id', 'integer'],
            [
                [
                    'profile__role'
                ],
                'safe'
            ]
        ]);
    }

    protected static function extraColumns()
    {
        return [
            'profile__full_name',
            'profile__phone_mobile',
            'profile__role'
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), self::extraColumns());
    }

    public function getQuery()
    {
        $query = parent::getQuery();

        return $query;
    }

    protected function prepareFilters($query)
    {
        parent::prepareFilters($query);
        static::filtersForExtraColumns($query);

        $query->andFilterWhere(['ordered_cards.project_id' => $this->project_id]);
    }
}
