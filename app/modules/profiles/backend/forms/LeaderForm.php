<?php

namespace modules\profiles\backend\forms;

use modules\profiles\backend\rbac\Rbac;
use modules\profiles\common\models\Leader;
use modules\profiles\common\models\LeaderAdminRegion;
use Yii;
use yii\base\Exception;
use yii2tech\ar\linkmany\LinkManyBehavior;

/**
 * @var array $profileIds
 */
class LeaderForm extends Leader
{
    /**
     * @var string
     */
    public $login;
    /**
     * @var string
     */
    public $password;
    /**
     * @var string
     */
    public $passwordCompare;

    //public $regions_for_role_admin;


    public function rules()
    {
        return array_merge(parent::rules(), [
            ['role', 'required'],

            //['region_id', 'integer'],
            /*[
                'region_id',
                'required',
                'when' => function (LeaderForm $model) {
                    return $model->roleManager->isAdminRegional();
                },
                'whenClient' => "function(attribute, value) {
                    return $('#leaderform-role').val() == '" . Rbac::ROLE_ADMIN_REGION . "';
                }",
            ],*/
            ['regionsForRoleAdmin', 'safe'],

            ['legal_person_id', 'integer'],
            /*['leader_id', 'required',
                'when' => function(LeaderForm $model) {
                    return $model->roleManager->isAdminOp();
                },
                'whenClient' => "function(attribute, value) {
                    return $('#leaderform-role').val() == '" . Rbac::ROLE_ADMIN_CURATOR . "';
                }",
            ],*/

            ['first_name', 'required'],

            ['last_name', 'required'],

            // ['middle_name', 'required'],

            ['phone_mobile_local', 'required'],

            ['email', 'required'],

            ['login', 'filter', 'filter' => 'trim'],
            ['login', 'string', 'max' => 32],
            ['login', 'required'],

            ['password', 'string'],
            ['password', 'string', 'max' => 32],
            [
                'password',
                'required',
                'when' => function (LeaderForm $model) {
                    return $model->isNewRecord;
                },
                'whenClient' => "function(attribute, value) {
                    return $('.js-is-new-record').val()
                }",
            ],

            ['passwordCompare', 'string'],
            [
                'passwordCompare',
                'required',
                'when' => function (LeaderForm $model) {
                    return $model->password;
                },
                'whenClient' => "function(attribute, value) {
                    return $('.js-is-new-record').val()
                }",
            ],
            ['passwordCompare', 'compare', 'compareAttribute' => 'password'],

            ['profileIds', 'safe'],
           // ['city_id', 'integer'],
            ['adminsOp', 'safe'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'login' => 'Логин',
            'password' => 'Пароль',
            'passwordCompare' => 'Подтверждение пароля',

        ]);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'linkProfilesBehavior' => [
                'class' => LinkManyBehavior::class,
                'relation' => 'profiles',
                'relationReferenceAttribute' => 'profileIds',
            ],
            'linkLeaderRegionBehavior' => [
                'class' => LinkManyBehavior::class,
                'relation' => 'regions',
                'relationReferenceAttribute' => 'regionsForRoleAdmin',
            ],
        ]);
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->login = $this->adminUser->login;
    }

    public function setAdminsOp($value)
    {
        $this->adminsOp = $value;
    }

    public function process()
    {
        $transaction = Yii::$app->db->beginTransaction();

        if (false === $this->validate()) {
            return false;
        }

        try {
            $isNewRecord = $this->isNewRecord;

            /*if ($this->roleManager->isAdminOp()) {
                $this->region_id = $this->adminRegion->region_id;
            }*/

            /*if ($this->roleManager->isAdminRegional() || $this->roleManager->isAdmin()) {
                $this->leader_id = null;
            }*/

            if (false === $this->save()) {
                return false;
            }

            if ($isNewRecord) {
                $adminUser = $this->createAdminUser($this->login, $this->password);

                if (false === $adminUser->save()) {
                    throw new Exception(implode(', ', $adminUser->getFirstErrors()));
                }

                $this->updateAttributes(['identity_id' => $adminUser->id]);

                //Добавляем связь лидеров (роль Администратор) и их регионов
                if (!empty($this->regions_for_role_admin)) {
                    $this->saveLeaderRegion($this->id, $this->regionsForRoleAdmin);
                }

            } else {
                $adminUser = $this->adminUser;

                $adminUser->login = $this->login;
                $adminUser->name = $this->full_name;
                $adminUser->email = $this->email;
                $adminUser->setRolesItems([$this->role]);

                if ($this->password) {
                    $adminUser->passhash = $adminUser->hashPassword($this->password);
                }

                //Добавляем связь лидеров (роль Администратор) и их регионов
                if (!empty($this->regions_for_role_admin)) {
                    $this->saveLeaderRegion($this->id, $this->regionsForRoleAdmin);
                }

                if (false === $adminUser->save()) {
                    throw new Exception(implode(', ', $adminUser->getFirstErrors()));
                }
            }

            $this->processAdminsOp();

            $transaction->commit();

            return true;

        } catch (Exception $e) {

            $this->addError('login', $e->getMessage());
            $transaction->rollBack();

            return false;
        }
    }

    /**
     * Сохраняем связь регионов и лидеров с ролью Администратор
     * @param $leaderId
     * @param array $regionId
     * @return bool
     */
    public function saveLeaderRegion($leaderId, array $regionId)
    {
        LeaderAdminRegion::deleteAll(['leader_id' => $leaderId]);
        foreach ($regionId as $idReg) {
            $newLeaderRegion = new LeaderAdminRegion();
            $newLeaderRegion->region_id = $idReg;
            $newLeaderRegion->leader_id = $leaderId;
            if (!$newLeaderRegion->save()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function processAdminsOp()
    {
        //Leader::updateAll(['leader_id' => null], ['leader_id' => $this->id]);

        if ($this->roleManager->isAdminOp()) {
            return false;
        }

        if (empty($this->adminsOp)) {
            return false;
        }

        foreach ($this->adminsOp as $index => $leaderId) {
            $adminOp = Leader::findOne(['id' => $leaderId]);

            if (null === $adminOp) {
                throw new Exception("Не найден сотрудник ОП #$leaderId");
            }

            $adminOp->leader_id = $this->id;

            if (false === $adminOp->save()) {
                throw new Exception("Не удалось сохранить дилеров: " . implode(', ', $adminOp->getFirstErrors()));
            }
        }

        return true;
    }

}