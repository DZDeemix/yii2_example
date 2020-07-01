<?php

namespace common\utils;

use modules\profiles\common\models\Profile;
use Yii;

class Notifier
{
    public static function profileRegistered(Profile $profile, $pw)
    {
        $domain = $_ENV['FRONTEND_WEB'] ?? null;
        Yii::$app->sms->send($profile->phone_mobile, "Вы зарегистрировались на {$domain}. Ваш пароль: " . $pw);

        if (!empty($profile->email)) {
            try {
                Yii::$app->mailer->compose('@modules/profiles/frontend/mail/registered', [
                    'pw' => $pw,
                    'profile' => $profile,
                ])
                    ->setSubject('Вы зарегистрировались на сайте')->setTo($profile->email)
                    ->send();
            }
            catch (\Exception $e) {
            }
        }
    }
}