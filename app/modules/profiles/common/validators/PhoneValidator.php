<?php

namespace modules\profiles\common\validators;

use yii\validators\Validator;
use marketingsolutions\phonenumbers\PhoneNumber;

class PhoneValidator extends Validator
{
    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @return bool
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->{$attribute};

        if (PhoneNumber::validate($value, 'RU') == false) {
            $model->addError($attribute, "Неверный номер телефона");

            return false;
        }

        return true;
    }

}