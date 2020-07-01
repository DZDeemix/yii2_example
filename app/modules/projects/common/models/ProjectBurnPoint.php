<?php


namespace modules\projects\common\models;


use marketingsolutions\finance\models\Purse;
use modules\burnpoints\common\models\BurnPoint;

/**
 * @inheritDoc
 * @property integer $project_id
 */
class ProjectBurnPoint extends BurnPoint
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
    public function beforeValidate()
    {
        $this->setProjectId();
        
        return parent::beforeValidate();
    }
    
    protected function setProjectId()
    {
        if (empty($this->project_id)) {
            $this->project_id = Purse::findOne($this->purse_id)->project_id;
        }
    }
}
