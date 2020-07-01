<?php

namespace modules\projects\common\models;

use marketingsolutions\finance\models\Purse;
use modules\projects\common\exceptions\MissingProjectException;
use ms\loyalty\finances\common\components\CompanyAccount;
use Yii;
use yz\Yz;

/**
 * @inheritDoc
 * @property integer $project_id
 */
class ProjectCompanyAccount extends CompanyAccount
{
    public static function getPurse()
    {
        if (!Project::$current) {
            throw new MissingProjectException();
        }

        if (!Project::$current->is_main) {
            return Project::$current->purse;
        }

        if (Yii::$app instanceof Yii\web\Application) {
            if (Yii::$app->request->pathInfo === 'finances/transactions/create') {
                $requestParams = Yii::$app->request->get('Transaction');

                if ($requestParams && !empty($requestParams['project_id'])) {
                    $projectId = (int) $requestParams['project_id'];
                    $project = Project::findOne($projectId);
                    return $project->purse;
                }
                else {
                    Yii::$app->session->setFlash(Yz::FLASH_INFO, 'Обязательно выберите в фильтре проект!');
                    Yii::$app->response->redirect('/finances/transactions/index');
                    Yii::$app->end();
                }
            }
        }

        return Project::$current->purse;
    }
}
