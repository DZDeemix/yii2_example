<?php

namespace modules\actions\backend\controllers;

use modules\actions\backend\models\ActionParticipantSearch;
use modules\actions\common\models\Action;
use modules\actions\common\models\ActionParticipant;
use modules\actions\common\models\ActionParticipantWithData;
use modules\profiles\common\managers\RoleManager;
use modules\sales\common\models\Sale;
use modules\sales\common\sales\statuses\Statuses;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yz\admin\actions\ExportAction;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\grid\columns\DataColumn;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\traits\CrudTrait;

/**
 * ActionParticipantController implements the CRUD actions for ActionParticipant model.
 */
class ActionParticipantController extends Controller implements AccessControlInterface
{
    use CrudTrait, CheckAccessTrait;

    public function actions()
    {
        return array_merge(parent::actions(), [
            'export' => [
                'class' => ExportAction::className(),
                'searchModel' => function ($params) {
                    /** @var ActionParticipantSearch $searchModel */
                    return Yii::createObject(ActionParticipantSearch::className());
                },
                'dataProvider' => function ($params, ActionParticipantSearch $searchModel) {
                    $dataProvider = $searchModel->search($params);
                    return $dataProvider;
                },
            ]
        ]);
    }

    /**
     * Lists all ActionParticipant models.
     * @return mixed
     */
    public function actionIndex()
    {
        /** @var ActionParticipantSearch $searchModel */
        $searchModel = Yii::createObject(ActionParticipantSearch::className());
        $searchModel->is_participant = true;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->setSort(['defaultOrder' => ['id' => SORT_DESC]]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => $this->getGridColumns(),
        ]);
    }

    public function getGridColumns()
    {

        return [
            [
                'attribute' => 'id',
                'label' => 'N',
            ],


            [
                'attribute' => 'action_id',
                'label' => 'Акция',
                'filter' => Action::getList(),
                'titles' => Action::getList(),
            ],


        ];
    }


    /**
     * Creates a new ActionParticipant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ActionParticipant;

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Record was successfully created'));
            return $this->getCreateUpdateResponse($model);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ActionParticipant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Record was successfully updated'));
            return $this->getCreateUpdateResponse($model);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing ActionParticipant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete(array $id)
    {
        $message = is_array($id) ?
            \Yii::t('admin/t', 'Records were successfully deleted') : \Yii::t('admin/t',
                'Record was successfully deleted');
        $id = (array)$id;

        foreach ($id as $id_) {
            $this->findModel($id_)->delete();
        }

        \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, $message);

        return $this->redirect(['index']);
    }

    /**
     * Finds the ActionParticipant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActionParticipant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ActionParticipant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
