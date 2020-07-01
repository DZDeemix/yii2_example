<?php

namespace modules\projects\common\traits;

use modules\projects\common\exceptions\MissingProjectException;
use modules\projects\common\models\Project;
use Yii;

trait ProjectSettingsTrait
{
    private static $_instance = null;

    public static function get()
    {
        if (!Project::$current) {
            throw new MissingProjectException();
        }

        if (empty(self::$_instance)) {
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

            $model = self::find()
                ->where(['project_id' => $projectId])
                ->limit(1)
                ->one();

            if ($model === null) {
                $model = new self;
                $model->project_id = $projectId;
                $model->save(false);
                $model->refresh();
            }

            self::$_instance = $model;
        }

        return self::$_instance;
    }
}
