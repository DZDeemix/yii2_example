<?php

namespace modules\projects\common\traits;

use marketingsolutions\finance\models\Purse;
use modules\projects\common\models\Project;
use Yii;
use yii\db\ActiveRecord;
use yii\web\ForbiddenHttpException;

trait ProfileMultipurseTrait
{
    public function getMultipurse(Project $project)
    {
        $purse = Purse::findOne([
           'owner_type' => self::class,
           'owner_id' => $this->id,
           'project_id' => $project->id
        ]);

        if (!$purse) {
            $purse = Purse::create(self::class, $this->id, strtr('Счет пользователя #{id} ({name}) по проекту #{pid}', [
                '{id}' => $this->id,
                '{name}' => $this->full_name,
                '{pid}' => $project->id,
            ]));
            $purse->project_id = $project->id;
            $purse->save(false);
        }

        return $purse;
    }
}