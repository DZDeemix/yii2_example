<?php

namespace modules\actions\frontend\forms;

use modules\actions\common\models\ActionParticipant;
use modules\actions\common\validators\ActionAllowValidator;
use modules\profiles\common\validators\ProfileExistsValidator;
use Yii;
use yii\base\Model;

class ActionParticipantForm extends Model
{
    /**
     * @var integer
     */
    public $profile_id;

    /**
     * @var integer
     */
    public $action_id;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['profile_id', 'required'],
            ['action_id', 'required'],
           // ['profile_id', ProfileExistsValidator::class, 'loadAttribute' => 'profile'],
           // ['action_id', ActionAllowValidator::class, 'profileIdAttribute' => 'profile_id'],
        ];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function process()
    {
        if (!$this->validate()) {
            return false;
        }

        $post_data = Yii::$app->request->post();
        $model = new ActionParticipant();
        $model->load($post_data, '');

        if ($model->save()) {
            return true;
        }
        return false;

    }
}