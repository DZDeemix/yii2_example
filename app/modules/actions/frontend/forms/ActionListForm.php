<?php

namespace modules\actions\frontend\forms;

use yii\base\Model;
use modules\actions\common\models\Action;
use modules\profiles\common\validators\ProfileExistsValidator;
use modules\profiles\common\models\Profile;

class ActionListForm extends Model
{
    /**
     * @var integer
     */
    public $profile_id;

    /**
     * @var Profile
     */
    public $profile;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['profile_id', 'required'],
            ['profile_id', ProfileExistsValidator::class, 'loadAttribute' => 'profile'],
        ];
    }

    /**
     * @return array|Action[]
     * @throws \yii\base\InvalidConfigException
     */
    public function search()
    {
        $actions = Action::find()
            ->actual($this->profile->role)
            ->confirmed($this->profile->id)
            ->all();

        $result = [];

        foreach ($actions as $action) {
            /* @var Action $action */
            if ($action->validator->validate($this->profile)) {
                $result[] = $action;
            }
        }

        return $result;
    }

}