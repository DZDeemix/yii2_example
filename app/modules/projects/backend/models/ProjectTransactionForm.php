<?php

namespace modules\projects\backend\models;

use marketingsolutions\finance\models\Purse;
use modules\projects\common\models\Project;
use ms\loyalty\finances\backend\forms\TransactionForm;
use yii\db\ActiveQuery;

/**
 * @inheritDoc
 * @property integer $project_id
 */
class ProjectTransactionForm extends TransactionForm
{
    public $project_id;

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['project_id', 'required'],
            ['project_id', 'in', 'range' => Project::find()->select('id')->column()],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'project_id' => 'Проект',
        ]);
    }

    /**
     * @return ActiveQuery
     */
    public static function find()
    {
        return parent::find()
            ->joinWith(['purse' => function (ActiveQuery $query) {
                $query
                    ->from(['purse' => Purse::tableName()]);
            }])
            ->where(['owner_type' => Project::class]);
    }
}
