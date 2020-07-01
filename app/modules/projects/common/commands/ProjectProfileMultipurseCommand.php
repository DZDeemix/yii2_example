<?php

namespace modules\projects\common\commands;

use modules\profiles\common\models\Profile;
use modules\projects\common\models\Project;
use yii\base\Component;
use Yii;

class ProjectProfileMultipurseCommand extends Component
{
    /** @var Profile */
    public $profile = null;

    public function handle()
    {
        /** @var Project[] $projects */
        $projects = Project::find()->all();

        if (empty($projects)) {
            return;
        }

        $profilesQuery = Profile::find();

        if ($this->profile) {
            $profilesQuery->andWhere(['id' => $this->profile->id]);
        }

        foreach ($profilesQuery->each() as $profile) {
            /** @var Profile $profile */
            foreach ($projects as $project) {
                $profile->getMultipurse($project);
            }
        }
    }

    public static function cacheBackground()
    {
        if (DIRECTORY_SEPARATOR == '\\') {
            # OS Windows
            $command = 'php ' . \Yii::getAlias('@base/yii') . ' projects/init/multipurse';
            $shell = new \COM("WScript.Shell");
            $shell->run($command, 0, false);
        }
        else {
            # Unix, Linux, Mac
            # $file = Yii::getAlias('@console/runtime/grades_create_history.log');
            $file = '/dev/null';
            $command = 'nohup php ' . \Yii::getAlias('@base/yii') . ' projects/init/multipurse';
            $command .= " > {$file} 2>&1 &";
            system($command);
        }
    }
}
