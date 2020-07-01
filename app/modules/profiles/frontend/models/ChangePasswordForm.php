<?php

namespace modules\profiles\frontend\models;

use modules\profiles\common\models\Profile;
use ms\loyalty\identity\phonesEmails\common\models\Identity;
use yii\base\Model;
use Yii;

/**
 * Class RegistrationForm
 *
 * @property Profile $profile
 */
class ChangePasswordForm extends Model
{
    /** @var Profile */
    public $profile;
    /** @var string */
    public $password;
    /** @var string */
    public $passwordCompare;

    public function rules()
    {
        return [
            ['password', 'string'],
            ['password', 'required'],
            ['passwordCompare', 'string'],
            ['passwordCompare', 'required'],
            [['passwordCompare'], 'compare', 'compareAttribute' => 'password'],
            ['password', 'checkPassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'passwordCompare' => 'Подтверждение пароля',
        ];
    }

    public function checkPassword()
    {
        if (preg_match('|^[a-zA-Z0-9\-\_\=\*\&\!\?\^\%\$\#\@\(\)\,\.]{5,30}$|', $this->password) == false) {
            $this->addError('password', 'Пароль должен быть длиной от 5 до 30 символов, может состоять из цифр, латинских букв и символов - _ = * & ! ? ^ % $ # @ ( ) , .');
        }
    }

    public function process()
    {
        if ($this->validate() == false) {
            return false;
        }

        $this->profile->setPasshash($this->password);

        return true;
    }
}