<?php

namespace modules\projects\common\traits;

use modules\projects\common\exceptions\MissingProjectException;
use modules\projects\common\models\Project;
use Yii;
use yii\db\ActiveRecord;
use yii\web\ForbiddenHttpException;

trait CheckProjectAccessTrait
{
    /**
     * @param $condition
     * @return ActiveRecord
     * @throws MissingProjectException
     * @throws ForbiddenHttpException
     */
    public static function findOne($condition)
    {
        if (!Project::$current) {
            throw new MissingProjectException();
        }

        $model = parent::findOne($condition);

        # в админке необходимо проверять, что при просмотре/редактировании модель принадлежит текущему проекту
        if (Yii::$app instanceof \yii\web\Application) {
            if (!Project::$current->is_main && $model->project_id !== Project::$current->id) {
                throw new ForbiddenHttpException('Project mismatch');
            }
        }

        return $model;
    }
}