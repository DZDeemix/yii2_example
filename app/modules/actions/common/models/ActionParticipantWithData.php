<?php

namespace modules\actions\common\models;

use modules\profiles\backend\rbac\Rbac;
use modules\profiles\common\models\Dealer;
use modules\profiles\common\models\Leader;
use modules\profiles\common\models\LeaderProfile;
use modules\profiles\common\models\Profile;
use modules\profiles\common\models\ProfileDealer;
use modules\profiles\common\Module;
use modules\sales\common\models\Sale;
use Yii;
use yii\db\Expression;
use yz\admin\search\WithExtraColumns;


class   ActionParticipantWithData extends ActionParticipant
{
    use WithExtraColumns;

    protected static function extraColumns()
    {
        return [
            'action__title',
            'action__id',
            'leader__full_name',
            'profile__full_name',
            'profile__role',
            'profile__id',
            'sale__status',
            'sale__id',
            'sale__bonuses',
            'sale_points__name',
            'sale_points__external_id',
            'sale_points__address',
        ];
    }

    public function attributes()
    {
        return array_merge(['confirmed'], parent::attributes(), static::extraColumns());
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'action__title' => 'Акция',
            'action__id' => 'Акция',
            'leader__full_name' => 'Представитель',
            'profile__full_name' => 'ФИО',
            'profile__role' => 'Роль',
            'sale__status' => 'Статус продажи',
            'sale__id' => 'ID продажи',
            'sale__bonuses' => 'Бонус продажи',
            'sale_points__name' => "Название Аптеки",
            'sale_points__external_id' => "ID Аптеки",
            'sale_points__address' => "Адрес Аптеки",
            'is_participant' => "Подтверждение участия в акции",
        ]);
    }

    public function getIs_participant()
    {
        return true;
    }

    public function getHas_sale()
    {
        return true;
    }

    public static function findOuter($action_id)
    {
        //Участники с индивидуальными планами

        $plan_action_profile_count = ActionProfile::find()->where(["action_id" => $action_id])->count();
        if ($plan_action_profile_count > 0) {

            $query2 = parent::find()
                ->select('0 AS `confirmed`, 1 AS `sale_exist`, null AS `id`, null AS `action_participant_id`, 
            null AS `created_at`, 
            null AS `action__title`, ' . $action_id . ' AS `action__id`, 
            `leader`.`full_name` AS `leader__full_name`, `profile`.
            `full_name` AS `profile__full_name`, `profile`.`role` AS `profile__role`,
            `profile`.`id` AS `profile__id`, null AS `sale__status`, null AS `sale__id`,
            null AS `sale__bonuses`, `sale_points`.`name` AS `sale_points__name`,
            `sale_points`.`external_id` AS `sale_points__external_id`, 
            `sale_points`.`address` AS `sale_points__address` ')->distinct()
                ->from(['profile' => Profile::tableName()])
                ->leftJoin(['action_profile' => ActionProfile::tableName()], 'action_profile.profile_id = profile.id')
                ->leftJoin(['profile_dealer' => ProfileDealer::tableName()],
                    'profile_dealer.id = (SELECT p1.id FROM {{%profiles_dealers}} AS p1  where profile.id=p1.profile_id ORDER BY p1.id LIMIT 1) ')
                ->leftJoin(['sale_points' => Dealer::tableName()], 'sale_points.id = profile_dealer.dealer_id')
                ->leftJoin(['leader_profile' => LeaderProfile::tableName()], 'leader_profile.profile_id = profile.id')
                ->leftJoin(['leader' => Leader::tableName()], 'leader_profile.leader_id = leader.id')
                ->where('action_profile.action_id=' . $action_id )
                ->andWhere('`profile`.`id` not in (select profile_id from yz_actions_participants where action_id=' . $action_id . ')')
            ;
        } else {
            $action = Action::findOne(["id" => $action_id]);
            $query2 = parent::find()
                ->select('0 AS `confirmed`, 1 AS `sale_exist`, null AS `id`, null AS `action_participant_id`, 
            null AS `created_at`, 
            null AS `action__title`, ' . $action_id . ' AS `action__id`, 
            `leader`.`full_name` AS `leader__full_name`, `profile`.
            `full_name` AS `profile__full_name`, `profile`.`role` AS `profile__role`,
            `profile`.`id` AS `profile__id`, null AS `sale__status`, null AS `sale__id`,
            null AS `sale__bonuses`, `sale_points`.`name` AS `sale_points__name`,
            `sale_points`.`external_id` AS `sale_points__external_id`, 
            `sale_points`.`address` AS `sale_points__address` ')->distinct()
                ->from(['profile' => Profile::tableName()])
                ->leftJoin(['profile_dealer' => ProfileDealer::tableName()],
                    'profile_dealer.id = (SELECT p1.id FROM {{%profiles_dealers}} AS p1  where profile.id=p1.profile_id ORDER BY p1.id LIMIT 1)')
                ->leftJoin(['sale_points' => Dealer::tableName()], 'sale_points.id = profile_dealer.dealer_id')
                ->leftJoin(['leader_profile' => LeaderProfile::tableName()], 'leader_profile.profile_id = profile.id')
                ->leftJoin(['leader' => Leader::tableName()], 'leader_profile.leader_id = leader.id')
                ->where('`profile`.`id` not in (select profile_id from yz_actions_participants where action_id=' . $action_id . ')')
                ->andWhere(['profile.role' => $action->role]);
        }
        return $query2;
    }

    public static function find()
    {
        $profileType = Yii::$app->getModule('profiles')->profileType;
        $leader = Leader::getLeaderByIdentity();
        switch ($profileType) {

            case Module::PROFILE_TYPE_DEALER_DISTRICT:
                $query = parent::find()
                    ->select(static::selectWithExtraColumns([
                        'id' => 'actionParticipant.id',
                        'created_at' => 'actionParticipant.created_at',
                        'action_participant_id' => 'actionParticipant.id',
                        'action__title' => '',
                    ]), ' 1 AS `confirmed`, 0 as `sale_exist`, ')->distinct()
                    ->from(['actionParticipant' => self::tableName()])
                    ->leftJoin(['profile' => Profile::tableName()], 'actionParticipant.profile_id = profile.id')
                    ->leftJoin(['action' => Action::tableName()], 'actionParticipant.action_id = action.id')
                    ->leftJoin(['profile_dealer' => ProfileDealer::tableName()],
                        'profile_dealer.id = (SELECT p1.id FROM {{%profiles_dealers}} AS p1 WHERE actionParticipant.profile_id=p1.profile_id ORDER BY p1.id LIMIT 1)')
                    ->leftJoin(['sale_points' => Dealer::tableName()], 'sale_points.id = profile_dealer.dealer_id')
                    ->leftJoin(['leader_profile' => LeaderProfile::tableName()],
                        'leader_profile.profile_id = profile.id')
                    ->leftJoin(['leader' => Leader::tableName()], 'leader_profile.leader_id = leader.id')
                    ->leftJoin(['sale' => Sale::tableName()],
                        'sale.action_id = action.id and sale.recipient_id = profile.id')// ->leftJoin(['positions' => SalePosition::tableName()], 'positions.leader_id = leader.id')
                ;


                //$query->union($query2);

                if ($leader && $leader->role == Rbac::ROLE_ADMIN_REGION) {
                    $query
                        ->leftJoin(['leaderProfileCurator' => LeaderProfile::tableName()],
                            'profile.id = leaderProfileCurator.profile_id')
                        ->leftJoin(['leaderCuratorRegionAdmin' => Leader::tableName()],
                            'leaderCuratorRegionAdmin.id = leaderProfileCurator.leader_id')
                        ->where([
                            'OR',
                            ['IS', 'leaderProfileCurator.leader_id', new Expression('NULL')],
                            ['leaderCuratorRegionAdmin.leader_id' => $leader->id]
                        ]);
                }
                if ($leader && $leader->role == Rbac::ROLE_ADMIN_CURATOR) {
                    // Regions filter
                    $query
                        ->leftJoin(['leaderProfile' => LeaderProfile::tableName()],
                            'profile.id = leaderProfile.profile_id')
                        ->Where([
                            'OR',
                            ['IS', 'leaderProfile.leader_id', new Expression('NULL')],
                            ['leaderProfile.leader_id' => $leader->id]
                        ]);
                }
                break;
            default:
                throw new \Exception("PROFILE_TYPE is not defined for Profiles module");
        }

        return $query;
    }

}
