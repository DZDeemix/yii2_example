<?php

namespace modules\projects\common\models;

use ms\loyalty\prizes\payments\common\models\Payment;

/**
 * @inheritDoc
 * @property integer $project_id
 */
class ProjectPayment extends Payment
{
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->updateUidFromProject();
        }
    }

    public function updateUidFromProject()
    {
        $project = Project::findOne($this->project_id);
        $uid = strtoupper($project->id1c) . '|' . $this->id;

        $this->updateAttributes(['uid' => $uid]);
    }
}
