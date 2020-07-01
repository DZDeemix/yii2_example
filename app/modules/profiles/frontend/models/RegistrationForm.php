<?php

namespace modules\profiles\frontend\models;

use common\utils\Notifier;
use ms\loyalty\location\common\models\City;
use ms\loyalty\location\common\models\Country;
use modules\profiles\common\models\Dealer;
use modules\profiles\common\models\Profile;
use ms\loyalty\location\common\models\Region;
use modules\profiles\common\Module;
use ms\loyalty\contracts\identities\IdentityRegistrarInterface;
use ms\loyalty\contracts\identities\RegistrationTokenManagerInterface;
use ms\loyalty\contracts\identities\TokenProvidesEmailInterface;
use ms\loyalty\contracts\identities\TokenProvidesFieldInterface;
use ms\loyalty\contracts\identities\TokenProvidesPhoneMobileInterface;
use yii\base\InvalidParamException;
use yii\base\Model;
use yii\db\Query;
use yii\web\IdentityInterface;
use Yii;

/**
 * Class RegistrationForm
 *
 * @property Profile $profile
 */
class RegistrationForm extends Model
{
    /**
     * @var IdentityInterface | \ms\loyalty\contracts\identities\IdentityInterface
     */
    public $identity;
    /**
     * @var string user password
     */
    public $password;
    /**
     * @var string user password repeat
     */
    public $passwordCompare;
    /**
     * @var bool
     */
    public $agreeWithTerms = true;
    /**
     * @var bool
     */
    public $allowPersonalDataProcessing = true;
    /**
     * @var \ms\loyalty\contracts\identities\RegistrationTokenManagerInterface
     */
    private $tokenManager;
    /**
     * @var RegistrationProfile
     */
    private $profile;
    /**
     * @var IdentityRegistrarInterface
     */
    private $registrar;
    /** @var bool */
    private $profileFound = null;
    /** @var integer */
    private $city_id;
    /** @var string */
    public $city_title = null;
    /** @var integer */
    public $dealer_id = null;
    /** @var string */
    public $dealer_name = null;
    /** @var string */
    public $dealer_address = null;

    public function __construct(RegistrationTokenManagerInterface $tokenManager, IdentityRegistrarInterface $registrar, $config = [])
    {
        $this->tokenManager = $tokenManager;
        $this->registrar = $registrar;
        parent::__construct($config);
    }

    public function init()
    {
        $this->profile = $this->findProfile();

        if ($this->profile) {
            $this->profileFound = true;
        }

        if ($this->profile === null) {
            $this->profile = $this->createProfile();
        }

        if ($this->profile === null) {
            throw new InvalidParamException('Profile with given properties was not found');
        }

        parent::init();
    }

    /**
     * @return RegistrationProfile | null
     */
    protected function findProfile()
    {
        if ($this->tokenManager instanceof TokenProvidesPhoneMobileInterface) {
            return RegistrationProfile::findOne(['phone_mobile' => $this->tokenManager->getPhoneMobile()]);
        }

        if ($this->tokenManager instanceof TokenProvidesEmailInterface) {
            return RegistrationProfile::findOne(['email' => $this->tokenManager->getEmail()]);
        }

        return null;
    }

    /**
     * @return Module
     */
    private function getModule()
    {
        return \Yii::$app->getModule('profiles');
    }

    protected function createProfile()
    {
        $profile = new RegistrationProfile();
        if ($this->tokenManager instanceof TokenProvidesPhoneMobileInterface) {
            $profile->phone_mobile = $this->tokenManager->getPhoneMobile();
        }
        if ($this->tokenManager instanceof TokenProvidesEmailInterface) {
            $profile->email = $this->tokenManager->getEmail();
        }
        if ($this->tokenManager instanceof TokenProvidesFieldInterface) {
            $profile->{$this->tokenManager->getFieldName()} = $this->tokenManager->getFieldValue();
        }

        return $profile;
    }

    public function rules()
    {
        return [
            ['password', 'string'],
            ['password', 'required'],
            ['passwordCompare', 'string'],
            ['passwordCompare', 'required'],
            ['passwordCompare', 'compare', 'compareAttribute' => 'password', /*'when' => function() { return $this->askPassword; }*/],
            ['agreeWithTerms', 'compare', 'compareValue' => 1,
                'message' => 'Вы должны согласиться с условиями участния в программе'],
            ['allowPersonalDataProcessing', 'compare', 'compareValue' => 1,
                'message' => 'Вы должны разрешить обработку своих персональных данных для участния в программе'],

            ['dealer_id', 'integer'],
            ['dealer_name', 'string'],
            ['dealer_address', 'string'],
            ['city_title', 'string'],
            ['city_title', 'checkCity'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'passwordCompare' => 'Подтверждение пароля',
            'agreeWithTerms' => 'Согласен / согласна с правилами участия в программе',
            'allowPersonalDataProcessing' => 'Даю согласие на обработку своих персональных данных',
            'dealer_name' => 'Компания',
            'dealer_address' => 'Адрес',
            'dealer_id' => 'Компания',
            'city_title' => 'Город',
        ];
    }

    public function loadAll($data, $formName = null)
    {
        $result = true;
        $result = $this->load($data, $formName) && $result;
        $result = $this->profile->load($data) && $result;

        return $result;
    }

    public function process()
    {
        if ($this->validateAll() === false) {
            return false;
        }

        /** @var Dealer $dealer */

        if (!empty($this->city_id) && !empty($this->dealer_name) && !empty($this->dealer_address)) {
            /** @var City $city */
            $city = City::findOne($this->city_id);
            $dealer = Dealer::findOne(['city_id' => $city->id, 'name' => $this->dealer_name]);
            if ($dealer == null) {
                $dealer = new Dealer();
                $dealer->city_id = $city->id;
                $dealer->name = $this->dealer_name;
                $dealer->address = $this->dealer_address;
                $dealer->save();
            }
        }
        elseif (empty($this->dealer_id)) {
            if (empty($this->city_id) || empty($this->dealer_name) || empty($this->dealer_address)) {
                $this->addError('dealer_id', 'Необходимо либо выбрать Компанию из списка, либо добавить новую с указанием названия, адреса и города');
                return false;
            }
            /** @var City $city */
            $city = City::findOne($this->city_id);
            $dealer = Dealer::findOne(['city_id' => $city->id, 'name' => $this->dealer_name]);

            if ($dealer == null) {
                $dealer = new Dealer();
                $dealer->city_id = $city->id;
                $dealer->name = $this->dealer_name;
                $dealer->address = $this->dealer_address;
                $dealer->save();
            }
        }
        else {
            $dealer = Dealer::findOne($this->dealer_id);
        }

        $transaction = \Yii::$app->db->beginTransaction();

        $this->profile->dealer_id = $dealer->id;

        if ($this->profileFound !== true) {
            $this->profile->blocked = true;
            $this->profile->blocked_at = (new \DateTime())->format('Y-m-d H:i:s');
            $this->profile->blocked_reason = 'Ваш личный кабинет заблокирован до подтверждения администратором портала. При подтверждении администратором Вы получите оповещение и Вам станут доступны все разделы программы';
        }

        if ($role = Yii::$app->session->get('role')) {
            $this->profile->role = $role;
        }

        $this->profile->save();
        $this->profile->refresh();

        $this->identity = $this->registrar->createForProfile($this->profile, $this->password, $this->tokenManager);

        $this->profile->updateAttributes([
            'identity_id' => $this->identity->getId(),
        ]);

        $transaction->commit();
        $this->tokenManager->remove();

        Notifier::userRegistered($this);

        return true;
    }

    public function checkCity()
    {
        if (!empty($this->dealer_id)) {
            return true;
        }

        if (!empty($this->city_title)) {
            $titles = explode(',', $this->city_title);
            $city = trim($titles[0]);
            $region = null;
            $country = null;

            if (isset($titles[2])) {
                $region = trim($titles[1]);
                $country = trim($titles[2]);
            }

            $query = (new Query())
                ->select('c.id')
                ->from(['c' => City::tableName()])
                ->leftJoin(['r' => Region::tableName()], 'r.id = c.region_id')
                ->leftJoin(['co' => Country::tableName()], 'co.id = c.country_id')
                ->where("c.title LIKE :cityName", [':cityName' => $city . '%'])
                ->andWhere("co.id = 1")
                ->limit(1);

            if ($country) {
                $query->andWhere("co.title LIKE :countryName", [':countryName' => $country . '%']);
            }

            $result = $query->one();

            if (empty($result)) {
                $this->addError('city_title', 'Город не найден');
            }
            else {
                $this->city_id = $result['id'];
            }
        }
    }

    public function validateAll()
    {
        $result = true;
        $result = $this->validate() && $result;
        $result = $this->profile->validate() && $result;
        return $result;
    }

    /**
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @return RegistrationTokenManagerInterface
     */
    public function getTokenManager()
    {
        return $this->tokenManager;
    }
}