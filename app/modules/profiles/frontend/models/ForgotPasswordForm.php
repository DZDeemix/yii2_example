<?php

namespace modules\profiles\frontend\models;

use modules\profiles\common\models\Profile;
use yii\base\Model;
use Yii;

/**
 * Class ForgotPasswordForm
 * @package modules\profiles\frontend\models
 */
class ForgotPasswordForm extends Model
{
    public $login_forgot;


    public function rules()
    {
        return [
            ['login_forgot', 'string'],
            ['login_forgot', 'required'],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(),
                'secret' => $_ENV['RECAPTCHA_SECRETKEY'] ?? null, 'uncheckedMessage' => 'Пожалуйста, подтвердите,что вы не робот'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'login_forgot' => 'Логин (Телефон)',
        ];
    }
}