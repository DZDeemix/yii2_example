<?php

namespace modules\sales\backend\models;

use modules\profiles\common\models\City;
use modules\profiles\common\models\Leader;
use modules\profiles\common\models\LeaderProfile;
use modules\profiles\common\models\Region;
use modules\projects\common\models\Project;
use modules\sales\common\models\Sale;
use modules\sales\common\models\SalePosition;
use yz\admin\search\WithExtraColumns;

class SaleSearchWithData extends SaleSearch
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
            'profile__role',
            'city__title',
            'region__name',
            'project__id',
            'position__product_id',
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
            'profile__role' => 'Роль',
            'profile__checked_at' => 'Участник проверен',
            'city__title' => 'Город',
            'region__name' => 'Регион',
            'project__id' => 'Юрлицо',
        ]);
    }

    protected function prepareQuery()
    {
        $query = static::find();

        $query
            ->select(self::selectWithExtraColumns(['sale.*']))
            ->from(['sale' => Sale::tableName()])
            ->joinWith(['profile profile'], false)
            ->leftJoin(['city' => City::tableName()], 'city.id = profile.city_id')
            ->leftJoin(['region' => Region::tableName()], 'region.id = city.region_id')
            ->leftJoin(['project' => Project::tableName()], 'project.id = sale.project_id')
            ->leftJoin(['position' => SalePosition::tableName()], 'position.sale_id = sale.id')
        ;

        $leader = Leader::getLeaderByIdentity();

        if ($leader) {
            if ($leader->roleManager->isAdminLegalPerson()) {
                $query->andWhere(
                    ['sale.project_id' => $leader->legal_person_id]
                );
            }
        }

        return $query;
    }

    protected function prepareFilters($query)
    {
        parent::prepareFilters($query);

        self::filtersForExtraColumns($query);
    }
}
