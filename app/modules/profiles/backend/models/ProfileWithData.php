<?php

namespace modules\profiles\backend\models;

use modules\profiles\common\models\City;
use modules\profiles\common\models\Dealer;
use modules\profiles\common\models\Profile;
use modules\profiles\common\models\Region;
use modules\profiles\common\Module;
use ms\loyalty\taxes\common\models\Account;
use ms\loyalty\taxes\common\models\AccountProfile;
use Yii;
use yz\admin\search\WithExtraColumns;

/**
 * Class ProfileWithData
 */
class ProfileWithData extends Profile
{
    use WithExtraColumns;

    protected static function extraColumns()
    {
        return [
            //'purse__balance',
            'dealer__name',
            'dealer__code',
            'city__title',
            'region__title',
            'region__name',
            'accountProfile__validated_at',
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), static::extraColumns());
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
          //  'purse__balance' => 'Баланс участника',
            'dealer__name' => 'Компания',
            'dealer__code' => 'Код Торговой точки',
            'city__title' => 'Город',
            'region__title' => 'Регион',
            'region__name' => 'Регион',
            'accountProfile__validated_at' => 'Дата подтверждения анкеты НДФЛ',
        ]);
    }

    public static function find()
    {
        $profileType = Yii::$app->getModule('profiles')->profileType;

        switch ($profileType) {
            case Module::PROFILE_TYPE_CITY_REGION:
                $query = parent::find()
                    ->select(static::selectWithExtraColumns(['profile.*']))
                    ->from(['profile' => self::tableName()])
                    ->joinWith('purse purse')
                    ->leftJoin(['account' => Account::tableName()], 'account.profile_id = profile.id')
                    ->leftJoin(['accountProfile' => AccountProfile::tableName()], 'accountProfile.account_id = account.id')
                    ->leftJoin(['dealer' => Dealer::tableName()], 'dealer.id = profile.city_id')
                    ->leftJoin(['city' => City::tableName()], 'city.id = profile.city_id')
                    ->leftJoin(['region' => Region::tableName()], 'region.id = city.region_id');
                break;

            case Module::PROFILE_TYPE_REGION:
                $query = parent::find()
                    ->select(static::selectWithExtraColumns(['profile.*']))
                    ->from(['profile' => self::tableName()])
                    ->joinWith('purse purse')
                    ->leftJoin(['account' => Account::tableName()], 'account.profile_id = profile.id')
                    ->leftJoin(['accountProfile' => AccountProfile::tableName()], 'accountProfile.account_id = account.id')
                    ->leftJoin(['dealer' => Dealer::tableName()], 'dealer.id = profile.dealer_id')
                    ->leftJoin(['city' => City::tableName()], 'city.id = profile.city_id')
                    ->leftJoin(['region' => Region::tableName()], 'region.id = profile.region_id');
                break;

            case Module::PROFILE_TYPE_DEALER_CITY_REGION:
                $query = parent::find()
                    ->select(static::selectWithExtraColumns(['profile.*']))
                    ->from(['profile' => self::tableName()])
                   // ->joinWith('purse purse')
                    ->leftJoin(['account' => Account::tableName()], 'account.profile_id = profile.id')
                    ->leftJoin(['accountProfile' => AccountProfile::tableName()], 'accountProfile.account_id = account.id')
                    ->leftJoin(['dealer' => Dealer::tableName()], 'dealer.id = profile.dealer_id')
                    ->leftJoin(['city' => City::tableName()], 'city.id = dealer.city_id')
                    ->leftJoin(['region' => Region::tableName()], 'region.id = city.region_id');
                break;

            case Module::PROFILE_TYPE_DEALER_REGION:
                $query = parent::find()
                    ->select(static::selectWithExtraColumns(['profile.*']))
                    ->from(['profile' => self::tableName()])
                    ->joinWith('purse purse')
                    ->leftJoin(['account' => Account::tableName()], 'account.profile_id = profile.id')
                    ->leftJoin(['accountProfile' => AccountProfile::tableName()], 'accountProfile.account_id = account.id')
                    ->leftJoin(['dealer' => Dealer::tableName()], 'dealer.id = profile.dealer_id')
                    ->leftJoin(['city' => City::tableName()], 'city.id = profile.city_id')
                    ->leftJoin(['region' => Region::tableName()], 'region.id = dealer.region_id');
                break;

            default:
                throw new \Exception("PROFILE_TYPE is not defined for Profiles module");
        }

        return $query;
    }
}
