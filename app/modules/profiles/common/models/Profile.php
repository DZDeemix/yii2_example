<?php

namespace modules\profiles\common\models;

use libphonenumber\PhoneNumberFormat;
use marketingsolutions\datetime\DateTimeBehavior;
use marketingsolutions\phonenumbers\PhoneNumber;
use modules\projects\backend\utils\MultipurseColumn;
use modules\projects\common\traits\ProfileMultipurseTrait;
use ms\loyalty\api\common\interfaces\SetPasswordInterface;
use ms\loyalty\api\common\interfaces\ValidateLoginPasswordInterface;
use ms\loyalty\api\common\models\Jwt;
use ms\loyalty\api\common\models\Log;
use ms\loyalty\api\common\traits\JwtLoginEmailPasshashTrait;
use ms\loyalty\api\common\traits\JwtLoginPhonePasshashTrait;
use ms\loyalty\api\common\traits\JwtProfileTrait;
use ms\loyalty\api\common\traits\SetPasswordTrait;
use ms\loyalty\contracts\identities\IdentityRoleInterface;
use ms\loyalty\contracts\prizes\PrizeRecipientInterface;
use ms\loyalty\contracts\profiles\HasEmail;
use ms\loyalty\contracts\profiles\HasEmailInterface;
use ms\loyalty\contracts\profiles\HasPhoneMobile;
use ms\loyalty\contracts\profiles\HasPhoneMobileInterface;
use ms\loyalty\contracts\profiles\ProfileInterface;
use ms\loyalty\contracts\profiles\UserName;
use ms\loyalty\contracts\profiles\UserNameInterface;
use marketingsolutions\finance\models\Purse;
use marketingsolutions\finance\models\PurseInterface;
use marketingsolutions\finance\models\PurseOwnerInterface;
use marketingsolutions\finance\models\PurseOwnerTrait;
use marketingsolutions\phonenumbers\PhoneNumberBehavior;
use ms\loyalty\taxes\common\models\Account;
use ms\loyalty\taxes\common\models\AccountProfile;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yz\admin\interfaces\OnlineIdsInterface;
use yz\interfaces\ModelInfoInterface;
use yii\web\IdentityInterface;
use yii\db\Query;

/**
 * This is the model class for table "yz_profiles".
 *
 * @property integer $id
 * @property integer $company_id
 * @property string $passhash
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $full_name
 * @property string $phone_mobile
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 * @property string $registered_at
 * @property string $birthday_on
 * @property string $role
 * @property string $specialty
 * @property string $gender
 * @property string $avatar
 * @property string $document
 * @property string $blocked_at
 * @property string $blocked_reason Reason of the user blocked
 * @property string $banned_at
 * @property string $banned_reason Reason of the user banned
 * @property integer $dealer_id
 * @property integer $city_id
 * @property integer $region_id
 * @property string $phone_confirmed_at
 * @property string $email_confirmed_at
 * @property string $uniqid
 * @property string $pers_at
 * @property string $checked_at
 * @property boolean $is_uploaded
 * @property integer $external_id
 * @property string $external_token
 * @property string $company_name
 * @property string $social_link
 *  @property string $is_checked
 *
 * @property string $phone_mobile_local
 * @property Dealer $dealer
 * @property \ms\loyalty\location\common\models\City $city
 * @property \ms\loyalty\location\common\models\Region $region
 * @property string $avatarUrl
 * @property boolean $confirmed
 * @property integer $age
 */
class Profile extends \yz\db\ActiveRecord implements IdentityInterface, \ms\loyalty\contracts\identities\IdentityInterface, ModelInfoInterface,
                                                     HasEmailInterface, HasPhoneMobileInterface, UserNameInterface, IdentityRoleInterface,
                                                     PurseOwnerInterface, PrizeRecipientInterface, ProfileInterface,
                                                     ValidateLoginPasswordInterface, SetPasswordInterface
{
    use HasEmail, HasPhoneMobile, UserName, PurseOwnerTrait;
    use JwtProfileTrait, JwtLoginEmailPasshashTrait, SetPasswordTrait;
    use ProfileMultipurseTrait;

    const SCENARIO_REGISTER = 'register';
    const AVATAR_DIR = 'photos';

    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';

    const ROLE_DEALER = 'dealer';
    const ROLE_RTT = 'rtt';
    const ROLE_DESIGNER = 'designer';

    const SPECIALTY_DEALER_LEADER = 'dealer_leader';
    const SPECIALTY_DEALER_MANAGER = 'dealer_manager';
    const SPECIALTY_RTT_LEADER = 'rtt_leader';
    const SPECIALTY_RTT_MANAGER = 'rtt_manager';

    const EVENT_AFTER_LOGIN = 'event_login';
    const EVENT_AFTER_REGISTER = 'event_register';

    public $avatar_local;
    public $city_local = null;
    private $_purses;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profiles}}';
    }

    public static function getCheckedOptions()
    {
        return [
            '1' => 'Проверен',
            '0' => 'Не проверен',
        ];
    }

    public static function genderOptions()
    {
        return [
            self::GENDER_MALE => 'Мужской',
            self::GENDER_FEMALE => 'Женский',
        ];
    }

    /**
     * Returns model title, ex.: 'Person', 'Book'
     *
     * @return string
     */
    public static function modelTitle()
    {
        return 'Профиль участника';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     *
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Профили участников';
    }

    /**
     * Returns purse's owner by owner's id
     *
     * @param int $id
     * @return $this
     */
    public static function findPurseOwnerById($id)
    {
        return static::findOne($id);
    }

    protected static function purseOwnerType()
    {
        return self::class;
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
            'phonenumber' => [
                'class' => PhoneNumberBehavior::className(),
                'attributes' => [
                    'phone_mobile_local' => 'phone_mobile',
                ],
                'defaultRegion' => 'RU',
            ],
            'datetime' => [
                'class' => DateTimeBehavior::className(),
                'performValidation' => false,
                'attributes' => [
                    'birthday_on' => [
                        'targetAttribute' => 'birthday_on_local',
                        'originalFormat' => ['date', 'yyyy-MM-dd'],
                        'targetFormat' => ['date', 'dd.MM.yyyy'],
                    ],
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['passhash', 'string', 'max' => 255],
            [['created_at', 'updated_at', 'company_name', 'social_link'], 'safe'],
            [['company_name', 'social_link'], 'string'],
            [['first_name', 'last_name', 'middle_name'], 'string', 'max' => 255],
            ['full_name', 'string', 'max' => 255],
            ['email', 'string', 'max' => 64],
            ['email', 'email'],
            ['email', 'unique'],
            ['phone_mobile', 'string', 'max' => 16],
            [['phone_mobile_local', 'role'], 'required'],
            ['phone_mobile_local', 'unique', 'targetAttribute' => ['phone_mobile' => 'phone_mobile']],
            ['dealer_id', 'integer'],
            ['company_id', 'integer'],
            ['city_id', 'integer'],
            ['city_local', 'checkCity'],
            ['region_id', 'integer'],
            ['gender', 'string'],
            ['avatar', 'string'],
            ['document', 'string'],
            ['role', 'string'],
            ['specialty', 'string'],
            ['avatar_local', 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'gif', 'ico'], 'maxSize' => 1024 * 1024 * 15],
            ['blocked_at', 'string'],
            ['blocked_reason', 'string'],
            ['banned_at', 'string'],
            ['banned_reason', 'string'],
            ['birthday_on', 'safe'],
            ['birthday_on_local', 'safe'],
            ['uniqid', 'string', 'max' => 50],
            [['email_confirmed_at', 'phone_confirmed_at', 'checked_at', 'is_uploaded', 'registered_at'], 'safe'],
            [['first_name', 'last_name'], 'required'],
            ['external_token', 'string', 'max' => 255],
            ['external_id', 'integer'],
            ['pers_at', 'string'],
            # обязательные поля
            [['email', 'first_name', 'last_name'], 'required', 'on' => self::SCENARIO_REGISTER],
            ['specialty', 'in', 'skipOnEmpty' => false, 'on' => self::SCENARIO_REGISTER,
                'range' => array_keys(Profile::getSpecialtyOptions()),
                'message' => 'Укажите должность',
            ],
            ['dealer_id', 'in', 'skipOnEmpty' => false, 'on' => self::SCENARIO_REGISTER,
                'range' => array_map('intval', Dealer::find()->select('id')->column()),
                'message' => 'Укажите компанию',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'passhash' => 'Хэш пароля',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'middle_name' => 'Отчество',
            'full_name' => 'Полное имя',
            'phone_mobile' => 'Номер телефона',
            'phone_mobile_local' => 'Номер телефона',
            'email' => 'Email',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата изменения',
            'registered_at' => 'Дата регистрации',
            'city_id' => 'Город',
            'city_local' => 'Город',
            'region_id' => 'Регион',
            'dealer_id' => 'Компания',
            'gender' => 'Пол',
            'role' => 'Роль',
            'specialty' => 'Должность',
            'avatar' => 'Аватар',
            'avatar_local' => 'Аватар',
            'document' => 'Документы',
            'blocked_at' => 'Заблокирован',
            'blocked_reason' => 'Причина блокировки',
            'banned_at' => 'Забанен',
            'banned_reason' => 'Причина бана',
            'birthday_on' => 'Дата рождения',
            'birthday_on_local' => 'Дата рождения',
            'email_confirmed_at' => 'Подтвердил e-mail',
            'phone_confirmed_at' => 'Подтвердил номер телефона',
            'checked_at' => 'Дата проверки участника',
            'is_uploaded' => 'Загружен по спискам',
            'pers_at' => 'Дата согласия на обработку персональных данных',
            'external_id' => 'Внешний ID',
            'external_token' => 'Внешний токен',
            'company_id' => 'Название компании',
            'social_link' => 'Ссылка на соц. сеть',
            'company_name' => 'Имя компании для дизайнеров/Архитекторов',
        ];
    }

    public function fields()
    {
        $fields = [
            'profile_id' => 'id',
            'company_name',
            'social_link',
            'full_name',
            'first_name',
            'last_name',
            'middle_name',
            'role',
            'specialty',
            'avatar_url' => 'avatarUrl',
            'phone_mobile',
            'email',
            'balance',
            'purses',
            'dealer_id',
            'dealer',
            'registered_at',
            'checked_at',
            'pers_at',
            'created_at',
            'blocked_at',
            'blocked_reason',
            'banned_at',
            'is_checked',
            'banned_reason',
            'account' => function (Profile $model) {
                /** @var Account $account */
                if ($account = Account::findOne(['profile_id' => $model->id])) {
                    /** @var AccountProfile $accountProfile */
                    if ($accountProfile = AccountProfile::findOne(['account_id' => $account->id])) {
                        return $accountProfile->toArray(['status', 'status_label']);
                    }
                }
                return null;
            },
            'city_local' => function (Profile $model) {
                return json_decode($model->city->request) ?: '';
            }
        ];

        return $fields;
    }

    public function getBalance() {
        $sum = 0;
        if(empty($this->_purses)) {
            $this->getPurses();
        }
        foreach ($this->_purses as $purse) {
            $sum += $purse['balance'];
        }
        return $sum ?? 0;
    }
    
    public  function  getPurses() {
        if(empty($this->_purses)) {
            $this->_purses = (new MultipurseColumn)::getPurses($this);
        }
        return $this->_purses;
    }
    
    public function beforeSave($insert)
    {
        $this->full_name = $this->first_name . ' ' . $this->last_name;

        if (!empty($this->birthday_on)) {
            $this->birthday_on = (new \DateTime($this->birthday_on))->format('Y-m-d H:i:s');
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return string
     */
    public function getIdentityRole()
    {
        return 'profile';
    }

    /**
     * Returns id of the recipient
     *
     * @return integer
     */
    public function getRecipientId()
    {
        return $this->getPrimaryKey();
    }

    public function getUserFullName()
    {
        return $this->full_name;
    }

    /**
     * Returns purce for the recipient, that should contain enough money
     *
     * @return PurseInterface
     */
    public function getRecipientPurse()
    {
        return $this->purse;
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     *
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     *
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     *
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    /**
     * @return integer
     */
    public function getProfileId()
    {
        return $this->getPrimaryKey();
    }

    public function getProfile()
    {
        return $this;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $this->uniqid = uniqid();
            $this->createPurse();
        }

        if (!$insert && $this->isAttributeChanged('full_name')) {
            $this->updatePurse();
        }

        $this->upload(['avatar']);

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return Jwt
     */
    public function generateJwtToken()
    {
        /** @var Jwt $jwt */
        if (null === ($jwt = Jwt::findOne(['profile_id' => $this->id]))) {
            $jwt = new Jwt();
            $jwt->profile_id = $this->id;
            $jwt->login = $this->phone_mobile;
            $jwt->token = Jwt::generateToken($this->id);
            $jwt->logged_at = (new \DateTime)->format('Y-m-d H:i:s');
            $jwt->logged_ip = Log::getIp();
            $jwt->save(false);
            $jwt->refresh();
        }

        return $jwt;
    }

    public function getAvatarUrl()
    {
        return empty($this->avatar)
            ? Yii::getAlias('@frontendWeb/images/avatar_blank.png') . "?v=3"
            : Yii::getAlias('@frontendWeb/data/' . self::AVATAR_DIR . '/' . $this->avatar);
    }

    public function getAge()
    {
        $now = new \DateTime('now');
        $birthday = new \DateTime($this->birthday_on);
        $diff = $birthday->diff($now);

        return intval($diff->y);
    }

    public function createPurse()
    {
        Purse::create(self::class, $this->id, strtr('Счет пользователя #{id} ({name})', [
            '{id}' => $this->id,
            '{name}' => $this->full_name,
        ]));
    }

    protected function updatePurse()
    {
        $this->purse->updateAttributes([
            'title' => strtr('Счет пользователя #{id} ({name})', [
                '{id}' => $this->id,
                '{name}' => $this->full_name,
            ]),
        ]);
    }

    public function afterDelete()
    {
        Purse::remove(self::class, $this->id);

        parent::afterDelete();
    }

    public function block($reason = null)
    {
        $this->updateAttributes([
            'blocked_at' => new Expression('NOW()'),
            'blocked_reason' => $reason,
        ]);
    }

    /**
     * Unblock profile from be able to login
     */
    public function unblock()
    {
        $this->updateAttributes([
            'blocked_at' => null,
            'blocked_reason' => null,
        ]);
    }

    public function ban($reason)
    {
        $this->updateAttributes([
            'banned_at' => new Expression('NOW()'),
            'banned_reason' => $reason,
        ]);
    }

    public function unban()
    {
        $this->updateAttributes([
            'banned_at' => null,
            'banned_reason' => null,
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDealer()
    {
        return $this->hasOne(Dealer::class, ['id' => 'dealer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(\ms\loyalty\location\common\models\City::class, ['id' => 'city_id']);
    }

    public function upload(array $fields)
    {
        foreach ($fields as $field) {
            $file = UploadedFile::getInstance($this, $field . '_local');

            if ($file instanceof UploadedFile) {
                $dir = Yii::getAlias("@data/photo");
                FileHelper::createDirectory($dir);
                $name = "{$this->id}_$field.{$file->extension}";
                $path = Yii::getAlias($dir . DIRECTORY_SEPARATOR . $name);
                $file->saveAs($path);
                $this->$field = $name;
                $this->updateAttributes([$field]);
            }
        }
    }

    public function updateRegisteredAt()
    {
        $this->updateTimeNow('registered_at');
    }

    public function updatePersAt()
    {
        $this->updateTimeNow('pers_at');
    }

    public function setPasshash($password)
    {
        $this->updateAttributes(['passhash' => \Yii::$app->security->generatePasswordHash($password)]);
    }

    public function confirmPhone()
    {
        $this->updateTimeNow('phone_confirmed_at');
    }

    public function confirmEmail()
    {
        $this->updateTimeNow('email_confirmed_at');
    }

    public static function generateRandomPassword($length = 5, $num = false)
    {
        $chars = $num ? '1234567890' : 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        $numChars = strlen($chars);
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }

    public function isMale()
    {
        return $this->gender == self::GENDER_MALE;
    }

    public function checkCity()
    {
        if (!empty($this->city_local)) {
            $titles = explode(',', $this->city_local);
            $city = trim($titles[0]);
            $region = null;

            if (isset($titles[1])) {
                $region = trim($titles[1]);
            }

            $query = (new Query())
                ->select('c.id')
                ->from(['c' => City::tableName()])
                ->leftJoin(['r' => Region::tableName()], 'r.id = c.region_id')
                ->where("c.title LIKE :cityName", [':cityName' => $city . '%'])
                ->limit(1);

            if ($region) {
                $query->andWhere("r.title LIKE :region", [':region' => $region . '%']);
            }

            $result = $query->one();

            if (empty($result)) {
                $this->addError('city_local', 'Город не найден');
            }
            else {
                $this->city_id = $result['id'];
            }
        }
        else {
            $this->addError('city_local', 'Пожалуйста, укажите свой город');
        }
    }

    public function checkPhone()
    {
        $phone = $this->phone_mobile_local;
        if (PhoneNumber::validate($phone, 'RU') == false) {
            $this->addError('phone_mobile_local', 'Неверный формат номера телефона');
            return;
        }

        $phone = PhoneNumber::format($phone, PhoneNumberFormat::E164, 'RU');

        if (Profile::findOne(['phone_mobile' => $phone])) {
            $this->addError('phone_mobile_local', 'Данный номер телефона уже используется');
        }
    }

    public function getDocuments()
    {
        $documents = empty($this->document) ? [] : explode(';', $this->document);
        $urls = [];
        $baseUrl = Yii::getAlias("@frontendWeb/data/profile-documents/");
        for ($i = 0; $i < count($documents); $i++) {
            $urls[] = $baseUrl . $documents[$i];
        }
        return $urls;
    }

    public function addDocument($filename)
    {
        $documents = empty($this->document) ? [] : explode(';', $this->document);
        $documents[] = $filename;
        $this->document = implode(';', $documents);
        $this->save(false);
    }

    public function removeDocument($filename)
    {
        $path = Yii::getAlias("@frontendWebroot/data/profile-documents/$filename");
        if (file_exists($path)) {
            @unlink($path);
        }

        $documents = empty($this->document) ? [] : explode(';', $this->document);
        $documents = array_diff($documents, [$filename]);
        $this->document = implode(';', $documents);
        $this->save(false);
    }

    public static function getRoleOptions()
    {
        return [
            self::ROLE_DEALER => 'Менеджер дистрибьютора',
            self::ROLE_RTT => 'Представитель торговой точки',
            self::ROLE_DESIGNER => 'Дизайнер/Архитектор',
        ];
    }

    public static function getSpecialtyOptions()
    {
        return [
            self::SPECIALTY_DEALER_LEADER => 'Руководитель отдела продаж',
            self::SPECIALTY_DEALER_MANAGER => 'Менеджер отдела продаж',
            self::SPECIALTY_RTT_LEADER => 'Руководитель в точке продаж',
            self::SPECIALTY_RTT_MANAGER => 'Продавец в точке продаж',
        ];
    }

    public static function getOptions()
    {
        $raw = (new Query)
            ->select(['p.id', 'p.phone_mobile', 'p.full_name'])
            ->from(['p' => Profile::tableName()])
            ->orderBy(['p.full_name' => SORT_ASC])
            ->all();

        $options = [];

        foreach ($raw as $r) {
            $key = $r['id'];
            $options[$key] = $r['full_name'] . '  ' . $r['phone_mobile'];
        }

        return $options;
    }

    private function updateTimeNow($attribute)
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $this->updateAttributes([$attribute => $now]);
    }
}
