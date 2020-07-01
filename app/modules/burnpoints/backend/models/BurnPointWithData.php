<?php


namespace modules\burnpoints\backend\models;


use modules\burnpoints\common\models\BurnPoint;
use modules\profiles\common\models\Profile;
use ms\loyalty\finances\common\models\Transaction;
use yz\admin\search\WithExtraColumns;

class BurnPointWithData extends BurnPointSearch
{
    use WithExtraColumns;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [self::extraColumns(), 'safe'],
        ]);
    }

    protected static function extraColumns()
    {
        return [
            'profile__full_name',
            'profile__phone_mobile',
            'profile__email',
            'transaction__title'
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), self::extraColumns());
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'profile__full_name' => 'Участник',
            'profile__phone_mobile' => 'Номер телефона',
            'profile__email' => 'E-mail',
            'transaction__title' => 'Транзакция'
        ]);
    }

    protected function prepareQuery()
    {
        $query = static::find();

        $query
            ->select(self::selectWithExtraColumns(['burnPoint.*']))
            ->from(['burnPoint' => BurnPoint::tableName()])
            ->leftJoin(['profile' => Profile::tableName()], 'burnPoint.profile_id = profile.id')
            ->leftJoin(['transaction' => Transaction::tableName()], 'burnPoint.transaction_id = transaction.id')
        ;

        return $query;
    }

    protected function prepareFilters($query)
    {
        parent::prepareFilters($query);

        self::filtersForExtraColumns($query);
    }
}