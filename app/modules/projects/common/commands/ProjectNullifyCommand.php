<?php


namespace modules\projects\common\commands;


use marketingsolutions\finance\models\Purse;
use modules\burnpoints\common\commands\NullifyCommand;
use modules\projects\common\exceptions\MissingProjectException;
use modules\projects\common\models\Project;

class ProjectNullifyCommand extends NullifyCommand
{
    public $project_id;
    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    protected function getTransactionPurse($purse_id)
    {
        $purse = Purse::findone($purse_id);
        $this->project_id = $purse->project_id;
        if (empty($this->project_id)) {
            throw new MissingProjectException();
        }
        
        return $purse;
    }
    
    /**
     * Returns company purse
     *
     * @throws MissingProjectException
     * @return Purse
     */
    protected function getCompanyPurse()
    {
        if (empty($this->project_id)) {
            throw new MissingProjectException();
        }
        
        $project = Project::findOne($this->project_id);
        
        return $project->purse;
    }
}
