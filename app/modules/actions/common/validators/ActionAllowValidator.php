<?php

namespace modules\actions\common\validators;

use yii\base\InvalidArgumentException;
use yii\validators\Validator;
use modules\profiles\common\models\Profile;
use modules\actions\common\models\Action;

/**
 * Validates action allow for Profile
 */
class ActionAllowValidator extends Validator
{
    /**
     * Attribute name with profile ID
     * @var string
     */
    public $profileIdAttribute;

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function validateAttribute($model, $attribute)
    {
        if (null === $this->profileIdAttribute) {
            throw new InvalidArgumentException("Property profileIdAttribute must be set");
        }

        if (false === $model->hasProperty($this->profileIdAttribute)) {
            throw new InvalidArgumentException("Model has not have property " . $this->profileIdAttribute);
        }

        $actionId = $model->{$attribute};
        $profileId = $model->{$this->profileIdAttribute};

        $profile = Profile::findOne(['id' => $profileId]);

        if (null === $profile) {
            $model->addError($this->profileIdAttribute, 'Участник не найден');

            return false;
        }

        /* @var Action $action */
        $action = Action::find()
            ->actual($profile->role)
            ->byId($actionId)
            ->one();

        if (null === $action) {
            $model->addError($attribute, 'Акция не найдена');

            return false;
        }

        if (false === $action->validator->validate($profile)) {
            $model->addError($attribute, 'Акция не доступна для участника');

            return false;
        }

        return true;
    }

}