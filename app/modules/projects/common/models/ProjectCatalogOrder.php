<?php

namespace modules\projects\common\models;

use ms\loyalty\catalog\common\models\CatalogOrder;

/**
 * @inheritDoc
 * @property integer $project_id
 */
class ProjectCatalogOrder extends CatalogOrder
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['project_id', 'required'],
            ['project_id', 'integer'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'project_id' => 'Проект'
        ]);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->updateUidFromProject();
        }
    }

    public function updateUidFromProject()
    {
        $uid = strtoupper($this->getLoyaltyName()) . '-EPS-' . $this->id;
        $this->updateAttributes(['uid' => $uid]);
    }

    /**
     * @return string
     */
    protected function getLoyaltyName()
    {
        $project = Project::findOne($this->project_id);

        return $project->id1c;
    }
}
