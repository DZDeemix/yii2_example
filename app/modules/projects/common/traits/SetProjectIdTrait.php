<?php

namespace modules\projects\common\traits;

use modules\projects\common\models\Project;
use Yii;

trait SetProjectIdTrait
{
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert && Project::$current && empty($this->project_id)) {
            if (Yii::$app instanceof Yii\console\Application) {
                # В консольном приложении проект всегда основной
                $projectId = Project::$current->id;
            }
            else {
                # В веб приложении основной проект может редактировать субпроекты
                $projectId = Project::$current->is_main && Yii::$app->request->get('project_id')
                    ? Yii::$app->request->get('project_id')
                    : Project::$current->id;
            }
            $this->project_id = $projectId;
        }

        return true;
    }
}
