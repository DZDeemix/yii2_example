<?php

namespace modules\profiles\frontend\forms;

use libphonenumber\PhoneNumberFormat;
use marketingsolutions\phonenumbers\PhoneNumber;
use modules\profiles\common\models\Profile;
use ms\loyalty\api\common\models\Token;
use yii\base\Model;
use Yii;

class RegisterFreeByPhoneForm extends Model
{
    /** @var string */
    public $token;

    /** @var string */
    public $phone;

    /** @var string */
    public $password;

    /** @var string */
    public $passwordConfirm;

    /** @var bool */
    public $checkedRules = false;

    /** @var bool */
    public $checkedPers = false;

    /** @var Profile */
    protected $profile = null;

    /** @var Token */
    protected $tokenModel = null;

    public function rules()
    {
        return [
            [['token', 'phone', 'password'], 'string', 'max' => 255],
            ['token', 'checkToken'],
            ['phone', 'checkProfile'],
            [['password', 'passwordConfirm'], 'required'],
            ['password', 'string', 'min' => 6],
            ['passwordConfirm', 'compare', 'compareAttribute' => 'password'],
            ['checkedPers', 'compare', 'compareValue' => 1, 'message' => 'Вы должны разрешить обработку своих персональных данных для участния в программе'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'passwordConfirm' => 'Подтверждение пароля',
            'checkedRules' => 'Согласен / согласна с правилами участия в программе',
            'checkedPers' => 'Даю согласие на обработку своих персональных данных',
        ];
    }

    public function checkToken()
    {
        if ($tokenModel = Token::findOne(['token' => $this->token, 'type' => Token::TYPE_SMS_NOPROFILE])) {
            $this->tokenModel = $tokenModel;
        }
        else {
            $this->addError('token', 'Ошибка токена. Регистрация доступна лишь по токену');
        }
    }

    public function checkProfile()
    {
        $phone = $this->phone;

        if (empty($phone) || PhoneNumber::validate($phone, 'RU') == false) {
            $this->addError('phone', 'Ошибка в номере телефона');
            return;
        }

        $phoneNumber = PhoneNumber::format($phone, PhoneNumberFormat::E164, 'RU');

        if (Profile::findOne(['phone_mobile' => $phoneNumber])) {
            $this->addError('phone', "По номеру $phoneNumber уже прошла регистрация");
        }

        $this->phone = $phoneNumber;
    }

    public function process()
    {
        if (!$this->validate()) {
            return false;
        }

        $profile = new Profile();
        $profile->load(Yii::$app->request->post(), '');
        $profile->phone_mobile_local = $this->phone;

        if ($profile->save()) {
            $profile->updateRegisteredAt();
            $profile->updatePersAt();
            $profile->setPasshash($this->password);
            $this->profile = $profile;
            $this->tokenModel->delete();

            return true;
        }

        $errors = array_values($this->profile->getFirstErrors());
        $this->addError('phone', $errors[0]);

        return false;
    }

    /**
     * @return Profile
     */
    public function getProfile()
    {
        $this->profile->refresh();

        return $this->profile;
    }
}