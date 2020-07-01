<?php

namespace modules\profiles\backend\models;
use modules\profiles\common\models\Profile;
use marketingsolutions\finance\models\Purse;
use marketingsolutions\finance\models\Transaction;
use yii\db\ActiveQuery;
use yz\admin\search\WithExtraColumns;


/**
 * Class ProfileTransaction
 * @property Profile $profile
 */
class ProfileTransaction extends Transaction
{
    use WithExtraColumns;

    /**
     * @return ActiveQuery
     */
    public static function find()
    {
        return parent::find()
            ->select(static::selectWithExtraColumns(['transaction.*']))
            ->from(['transaction' => ProfileTransaction::tableName()])
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
            'profile__full_name',
            'profile__phone_mobile',
            'profile__city_id',
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), static::extraColumns());
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'purse__balance' => 'Баланс',
            'profile__full_name' => 'Участник',
            'profile__phone_mobile' => 'Номер телефона',
        ]);
    }

    /**
     * @return ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['id' => 'owner_id'])
            ->via('purse');
    }

}