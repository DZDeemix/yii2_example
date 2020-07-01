<?php

namespace modules\profiles\common\validators;

use yii\validators\Validator;
use modules\profiles\common\models\Profile;

/**
 * Validates profile exists by ID
 */
class ProfileExistsValidator extends Validator
{
    /**
     * Loads profile model into owner $model->{loadAttribute} property if exists
     * @var null|string
     */
    public $loadAttribute;

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @return bool
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->{$attribute};

        $profile = Profile::findOne(['id' => $value]);

        if (null === $profile) {
            $model->addError($attribute, 'Не найден профиль участника');

            return false;
        }

        if ($this->loadAttribute && $model->hasProperty($this->loadAttribute)) {
            $model->{$this->loadAttribute} = $profile;
        }

        return true;
    }

}