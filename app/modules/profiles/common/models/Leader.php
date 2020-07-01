<?php

namespace modules\profiles\common\models;

use marketingsolutions\phonenumbers\PhoneNumberBehavior;
use modules\profiles\backend\rbac\Rbac;
use modules\profiles\common\managers\LeaderRoleManager;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yz\admin\models\User;
use yz\interfaces\ModelInfoInterface;

/**
 * @property integer $id
 * @property integer $identity_id
 * @property string $role
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $full_name
 * @property string $phone_mobile
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 * @property integer $legal_person_id
 *
 * @property string $phone_mobile_local
 * @property LeaderRoleManager $roleManager
 * @property User $adminUser
 * @property Leader $adminRegion
 * @property Leader[] $adminsOp
 * @property LeaderProfile[] $leaderProfiles
 * @property Profile[] $profiles
 * @property string $backendUserProfileType
 * @property string $backendUserProfileTitle
 */
class Leader extends ActiveRecord implements ModelInfoInterface
{
    /**
     * @var LeaderRoleManager
     */
    private $_roleManager;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%leaders}}';
    }

    /**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return 'Администратор';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Администраторы';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
            'phonenumber' => [
                'class' => PhoneNumberBehavior::class,
                'attributes' => [
                    'phone_mobile_local' => 'phone_mobile',
                ],
                'defaultRegion' => 'RU',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['identity_id', 'integer'],

            ['role', 'string', 'max' => 16],
            ['role', 'in', 'range' => array_keys(Rbac::getRolesList())],

            //['region_id', 'integer'],

            //['leader_id', 'integer'],

            ['first_name', 'filter', 'filter' => 'trim'],
            ['first_name', 'string', 'max' => 32],

            ['last_name', 'filter', 'filter' => 'trim'],
            ['last_name', 'string', 'max' => 32],

            ['middle_name', 'filter', 'filter' => 'trim'],
            ['middle_name', 'string', 'max' => 32],

            ['full_name', 'filter', 'filter' => 'trim'],
            ['full_name', 'string', 'max' => 255],

            ['phone_mobile', 'string', 'max' => 16],
            ['phone_mobile', 'unique'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'string', 'max' => 128],
            ['email', 'email'],
            ['email', 'unique'],

            ['created_at', 'safe'],
            ['updated_at', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'N',
            'identity_id' => 'Identity ID',
            //'region_id' => 'Округ',
           // 'city_id' => 'Город',
            'leader_id' => 'Администратор региона',
            'role' => 'Роль',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'middle_name' => 'Отчество',
            'full_name' => 'Полное имя',
            'full_name_with_id' => 'Полное имя',
            'phone_mobile' => 'Телефон',
            'phone_mobile_local' => 'Телефон',
            'email' => 'Email',
            'login' => 'Логин',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'identity_id' ,
            'city_id',
           // 'region_id',
           // 'leader_id',
            'role' ,
            'first_name' ,
            'last_name' ,
            'middle_name',
            'full_name',
            'full_name_with_id' ,
            'phone_mobile' ,
            'email' ,
            'created_at' ,
            'updated_at' ,
        ];
    }

    public function beforeSave($insert)
    {
        $this->full_name = $this->getFullName();

        return parent::beforeSave($insert);
    }

    /**
     * @return string
     */
    public function getFull_Name_With_Id()
    {
        return $this->last_name . ' ' . $this->first_name . ' ' . $this->middle_name. ' | ' . $this->id;
    }

    public function beforeDelete()
    {
       /* if ($this->roleManager->isAdminRegional()) {
            Leader::updateAll(['leader_id' => null], ['leader_id' => $this->id]);
        }

        LeaderProfile::deleteAll(['leader_id' => $this->id]);*/

        return parent::beforeDelete();
    }

    public function afterDelete()
    {
        User::deleteAll(['id' => $this->identity_id]);

        parent::afterDelete();
    }

    /**
     * @return LeaderRoleManager
     */
    public function getRoleManager()
    {
        if ($this->_roleManager === null) {
            $this->_roleManager = new LeaderRoleManager($this);
        }

        return $this->_roleManager;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->last_name . ' ' . $this->first_name . ' ' . $this->middle_name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminUser()
    {
        return $this->hasOne(User::class, ['id' => 'identity_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
   /* public function getRegion()
    {
        return $this->hasOne(Region::class, ['id' => 'region_id']);
    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
   /* public function getAdminRegion()
    {
        return $this->hasOne(Leader::class, ['id' => 'leader_id']);
    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminsOp()
    {
        return $this->hasMany(Leader::class, ['leader_id' => 'id']);
    }

   /* public function getRegions()
    {
        return $this->hasMany(Region::class, ['id' => 'region_id'])->viaTable(LeaderAdminRegion::tableName(),
            ['leader_id' => 'id']);
    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeaderProfiles()
    {
       // return $this->hasMany(LeaderProfile::class, ['leader_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return null;//$this->hasMany(Profile::class, ['id' => 'profile_id'])
            //->via('leaderProfiles');
    }

    /**
     * @return array
     */
    public function getAdminProfiles()
    {
        /*if (isset(\Yii::$app->user->id)) {
            $leaderId = User::find()
                ->select('{{%leaders}}.id as id_leader')
                ->leftJoin('{{%leaders}}', '{{%leaders}}.identity_id={{%admin_users}}.id')
                ->where(['{{%admin_users}}.id' => \Yii::$app->user->id])
                ->asArray()
                ->one();
            $regionIds = LeaderAdminRegion::find()->select('region_id')->where(['leader_id' => $leaderId['id_leader']])->column();
            return City::find()->select('id')->where(['region_id' => $regionIds])->column();
        }
        return [];*/

    }

    /**
     * @param string $login
     * @param string $password
     * @return User
     */
    public function createAdminUser(string $login, string $password)
    {
        $user = new User;
        $user->login = $login;
        $user->is_super_admin = false;
        $user->is_active = true;
        $user->name = $this->full_name ? $this->full_name : $this->getFullName();
        $user->email = $this->email;
        $user->is_identity = true;
        $user->profile_finder_class = LeaderFinder::class;
        $user->passhash = $user->hashPassword($password);
        $user->setRolesItems([$this->role]);

        return $user;
    }

    /**
     * @param string $role Leader role condition
     * @return array
     */
    public static function getOptions(string $role = null)
    {
        $query = self::find()
            ->innerJoinWith(['region'])
            ->select([
                "CONCAT({{%leaders}}.full_name, ' (', {{%regions}}.title, ')') AS full_name",
                "{{%leaders}}.id",
            ])
            ->orderBy(['{{%leaders}}.full_name' => SORT_ASC])
            ->indexBy('id');

        if ($role) {
            $query->andWhere(['role' => $role]);
        }

        return $query->column();
    }

    /**
     * @param string $role Leader role condition
     * @return array
     */
    public static function getLeaderOptions(string $role = null)
    {
        $query = self::find()
            ->select([
                "CONCAT({{%leaders}}.full_name) AS full_name",
                "{{%leaders}}.id",
            ])
            ->orderBy(['{{%leaders}}.full_name' => SORT_ASC])
            ->indexBy('id');

        if ($role) {
            $query->andWhere(['role' => $role]);
        }

        return $query->column();
    }

    /**
     * @param string $role Leader role condition
     * @return array
     */
    public static function getСurators(string $role = null)
    {
        $query = self::find()
            ->select([
                "{{%leaders}}.full_name  AS full_name",
                "{{%leaders}}.id",
            ])
            ->orderBy(['{{%leaders}}.full_name' => SORT_ASC])
            ->indexBy('id');

        if ($role) {
            $query->andWhere(['role' => $role]);
        }

        return $query->column();
    }

    /**
     * @return Leader|null
     */
    public static function getLeaderByIdentity()
    {
        $user = Yii::$app->user;

        if (false == $user) {
            return null;
        }

        $leader = Leader::findOne(['identity_id' => $user->identity->getId()]);

        return $leader;
    }

    public static function getLeaderRegion()
    {
       /* if (isset(\Yii::$app->user->id)) {
            $leaderId = User::find()
                ->select('{{%leaders}}.id as id_leader')
                ->leftJoin('{{%leaders}}', '{{%leaders}}.identity_id={{%admin_users}}.id')
                ->where(['{{%admin_users}}.id' => \Yii::$app->user->id])
                ->asArray()
                ->one();
            return LeaderAdminRegion::find()->select('region_id')->where(['leader_id' => $leaderId['id_leader']])->column();
        }*/
        return [];
    }

    public static function getLeaderRegions()
    {
        return [];
    }

    public static function getBackendUserProfileType()
    {
        return "";
    }

    public static function getBackendUserProfileTitle()
    {
        return "";
    }

}
