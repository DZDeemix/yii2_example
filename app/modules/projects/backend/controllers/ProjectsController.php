<?php

namespace modules\projects\backend\controllers;

use modules\projects\common\commands\ReportExtranetCommand;
use modules\projects\ProjectsModuleTrait;
use Yii;
use modules\projects\common\models\Project;
use modules\projects\backend\models\ProjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yz\admin\actions\ExportAction;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\traits\CrudTrait;
use yz\admin\contracts\AccessControlInterface;

/**
 * ProjectsController implements the CRUD actions for Project model.
 */
class ProjectsController extends Controller implements AccessControlInterface
{
    use CrudTrait, CheckAccessTrait, ProjectsModuleTrait;

    public function actions()
    {
        return array_merge(parent::actions(), [
            'export' => [
                'class' => ExportAction::className(),
                'searchModel' => function ($params) {
                    /** @var ProjectSearch $searchModel */
                    return Yii::createObject(ProjectSearch::className());
                },
                'dataProvider' => function ($params, ProjectSearch $searchModel) {
                    $dataProvider = $searchModel->search($params);
                    return $dataProvider;
                },
            ]
        ]);
    }

    /**
     * Lists all Project models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        /** @var ProjectSearch $searchModel */
        $searchModel = Yii::createObject(ProjectSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => $this->getGridColumns($searchModel),
        ]);
    }

    public function getGridColumns(ProjectSearch $searchModel)
    {
        return [
            'title',
            'id1c',
            'is_enabled:boolean',
            'created_at:datetime',
            [
                'label' => 'Сумма входящих',
                'value' => function (Project $model) {
                    return $model->calculateTransactionsSumIncoming() / 100;
                }
            ],
            [
                'label' => 'Сумма исходящих',
                'value' => function (Project $model) {
                    return $model->calculateTransactionsSumOutbound() / 100;
                }
            ],
            [
                'label' => 'Баланс проекта',
                'format' => 'raw',
                'value' => function (Project $model) {
                    return ($model->purse->balance / 100) . " <i class='fa fa-ruble-sign purse-balance'></i>";
                }
            ],
        ];
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = \Yii::createObject(Project::class);
        $model->is_enabled = true;

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Record was successfully created'));
            return $this->getCreateUpdateResponse($model);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS,
                \Yii::t('admin/t', 'Record was successfully updated'));

            return $this->redirect(\Yii::$app->request->referrer);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete(array $id)
    {
        $message = is_array($id) ?
            \Yii::t('admin/t', 'Records were successfully deleted') : \Yii::t('admin/t', 'Record was successfully deleted');
        $id = (array) $id;

        foreach ($id as $id_) {
            $this->findModel($id_)->delete();
        }

        \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, $message);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
