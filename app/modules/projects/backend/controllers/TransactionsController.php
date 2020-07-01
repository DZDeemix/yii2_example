<?php

namespace modules\projects\backend\controllers;

use marketingsolutions\finance\models\Purse;
use modules\projects\backend\models\ProjectTransactionForm;
use modules\projects\common\models\Project;
use ms\loyalty\finances\common\finances\BackendUserPartner;
use Yii;
use yii\web\Controller;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\helpers\AdminHtml;
use yz\admin\models\AdminActivityRecord;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\traits\CrudTrait;
use yz\Yz;

/**
 * TransactionsController implements the CRUD actions for Transaction model.
 */
class TransactionsController extends Controller implements AccessControlInterface
{
    use CrudTrait, CheckAccessTrait;

    /**
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProjectTransactionForm();
        $model->partner_type = BackendUserPartner::class;
        $model->partner_id = Yii::$app->user->id;

        if ($model->load(\Yii::$app->request->post())) {
            /** @var Purse $purse */
            $project = Project::findOne($model->project_id);
            $purse = $project->purse;
            $oldBalance = $purse->balance / 100;

            $purse->addTransaction($model);
            $purse->refresh();
            \Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Record was successfully created'));

            AdminActivityRecord::createRecord([
                'comment' => 'Изменение баланса компании: ' . ($model->type == 'in' ? '+' : '-') . $model->amount,
                'old_value' => $oldBalance . '',
                'new_value' => ($purse->balance / 100) . '',
            ]);

            return $this->getCreateUpdateResponse($model, [
                AdminHtml::ACTION_SAVE_AND_STAY => function () use ($model, $purse) {
                    return $this->redirect(['/finances/transactions/index',
                        'ProjectTransactionSearch[purse_id]' => $purse->id]);
                },
            ]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);

    }
}
