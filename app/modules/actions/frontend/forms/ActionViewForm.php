<?php

namespace modules\actions\frontend\forms;

use yii\base\Model;
use modules\actions\common\models\Action;
use modules\actions\frontend\models\ApiAction;

class ActionViewForm extends Model
{
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
            ['action_id', 'required'],
            ['action_id', 'integer'],
            ['action_id', 'exist',
                'targetClass' => Action::class,
                'targetAttribute' => ['action_id' => 'id'],
                'message' => 'Акция не найдена'
            ],
        ];
    }

    /**
     * @return array|null|ApiAction
     * @throws \yii\base\InvalidConfigException
     */
    public function search()
    {
        return ApiAction::find()
            ->joinWith([
                'groups',
                'categories',
                'products',
            ])
            ->where([
                '{{%actions}}.id' => $this->action_id
            ])
            ->one();
    }

}