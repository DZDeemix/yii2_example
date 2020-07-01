<?php

namespace modules\projects\common\commands;

use marketingsolutions\finance\models\Purse;
use modules\projects\common\exceptions\MissingProjectException;
use modules\projects\common\models\Project;
use ms\loyalty\prizes\payments\common\commands\SendPaymentCommand;

/**
 * @inheritDoc
 */
class ProjectSendPaymentCommand extends SendPaymentCommand
{
    /**
     * Returns company purse
     *
     * @throws MissingProjectException
     * @return Purse
     */
    protected function getCompanyPurse()
    {
        if (empty($this->payment->project_id)) {
            throw new MissingProjectException();
        }

        $project = Project::findOne($this->payment->project_id);

        return $project->purse;
    }
}
