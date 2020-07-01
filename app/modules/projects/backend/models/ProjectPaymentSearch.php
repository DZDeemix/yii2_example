<?php

namespace modules\projects\backend\models;

use modules\profiles\common\models\Profile;
use ms\loyalty\prizes\payments\backend\models\PaymentSearch;
use ms\loyalty\prizes\payments\common\models\Payment;
use yz\admin\search\WithExtraColumns;

/**
 * @inheritDoc
 * @property integer $project_id
 */
class ProjectPaymentSearch extends PaymentSearch
{
    use WithExtraColumns;

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['project_id', 'integer'],
            [
                [
                    'profile__full_name',
                    'profile__phone_mobile',
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

    protected function prepareQuery()
    {
        return self::find()
            ->select(self::selectWithExtraColumns(['payment.*']))
            ->from(['payment' => Payment::tableName()])
            ->leftJoin(['profile' => Profile::tableName()], 'payment.recipient_id = profile.id');
    }

    protected function prepareFilters($query)
    {
        $query->andFilterWhere(['payment.project_id' => $this->project_id]);
        $query->andFilterWhere(['payment.project_id' => $this->project_id]);
        static::filtersForExtraColumns($query);

        parent::prepareFilters($query);
    }
}
