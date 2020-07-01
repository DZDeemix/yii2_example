<?php

namespace modules\sales\backend\models;

use modules\profiles\common\models\City;
use modules\profiles\common\models\Profile;
use modules\profiles\common\models\Region;
use modules\sales\common\models\Group;
use modules\sales\common\models\Product;
use modules\sales\common\models\Sale;
use modules\sales\common\models\SalePosition;
use yz\admin\search\WithExtraColumns;

class SalePositionSearchWithData extends SalePositionSearch
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
            'profile__checked_at',
            'city__title',
            'region__name',
            'sale__status',
            'sale__created_at',
            'sale__updated_at',
            'sale__sold_on',
            'sale__number',
            'sale__place',
            'sale__total_cost',
            'group__id',
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), self::extraColumns());
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'profile__full_name' => 'ФИО участника',
            'profile__phone_mobile' => 'Номер телефона',
            'profile__email' => 'E-mail',
            'profile__checked_at' => 'Участник проверен',
            'city__title' => 'Город',
            'region__name' => 'Регион',
            'sale__status' => 'Статус продажи',
            'sale__created_at' => 'Дата внесения',
            'sale__sold_on' => 'Дата продажи',
            'sale__number' => 'Номер',
            'sale__place' => 'Место продажи',
            'sale__total_cost' => 'Стоимость материалов',
            'group__id' => 'Бренд',
        ]);
    }

    protected function prepareQuery()
    {
        $query = static::find();

        $query
            ->select(self::selectWithExtraColumns(['sp.*']))
            ->from(['sp' => SalePosition::tableName()])
            ->leftJoin(['product' => Product::tableName()], 'product.id = sp.product_id')
            ->leftJoin(['group' => Group::tableName()], 'group.id = product.group_id')
            ->leftJoin(['sale' => Sale::tableName()], 'sale.id = sp.sale_id')
            ->leftJoin(['profile' => Profile::tableName()], 'profile.id = sale.recipient_id')
            ->leftJoin(['city' => City::tableName()], 'city.id = profile.city_id')
            ->leftJoin(['region' => Region::tableName()], 'region.id = city.region_id')
        ;

        return $query;
    }

    protected function prepareFilters($query)
    {
        parent::prepareFilters($query);

        self::filtersForExtraColumns($query);
    }
}
