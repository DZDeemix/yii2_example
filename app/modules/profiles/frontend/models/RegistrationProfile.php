<?php

namespace modules\profiles\frontend\models;
use modules\profiles\common\models\Profile;


/**
 * Class RegistrationProfile
 */
class RegistrationProfile extends Profile
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['first_name', 'required'],
            ['last_name', 'required'],
            ['email', 'required'],
        ]);
    }
}