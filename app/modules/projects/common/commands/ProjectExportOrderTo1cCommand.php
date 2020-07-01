<?php

namespace modules\projects\common\commands;

use marketingsolutions\finance\models\Purse;
use modules\projects\common\exceptions\MissingProjectException;
use modules\projects\common\models\Project;
use ms\loyalty\catalog\common\commands\ExportOrderTo1cCommand;

/**
 * @inheritDoc
 */
class ProjectExportOrderTo1cCommand extends ExportOrderTo1cCommand
{
    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    protected function getLoyaltyName()
    {
        if (empty($this->catalogOrder->project_id)) {
            throw new MissingProjectException();
        }

        $project = Project::findOne($this->catalogOrder->project_id);

        return $project->id1c;
    }

    /**
     * Returns company purse
     *
     * @throws MissingProjectException
     * @return Purse
     */
    protected function getCompanyPurse()
    {
        if (empty($this->catalogOrder->project_id)) {
            throw new MissingProjectException();
        }

        $project = Project::findOne($this->catalogOrder->project_id);

        return $project->purse;
    }
}
