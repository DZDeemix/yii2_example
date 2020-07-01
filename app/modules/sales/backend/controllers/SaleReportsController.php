<?php

namespace modules\sales\backend\controllers;

use modules\actions\common\models\Action;
use modules\profiles\common\models\Profile;
use Yii;
use modules\sales\common\models\SaleReport;
use modules\sales\backend\models\SaleReportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yz\admin\actions\ExportAction;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\traits\CrudTrait;
use yz\admin\contracts\AccessControlInterface;

/**
 * SaleReportsController implements the CRUD actions for SaleReport model.
 */
class SaleReportsController extends Controller implements AccessControlInterface
{
    use CrudTrait, CheckAccessTrait;

    public function actions()
    {
        return array_merge(parent::actions(), [
            'export' => [
                'class' => ExportAction::className(),
                'searchModel' => function($params) {
                    /** @var SaleReportSearch $searchModel */
                    return Yii::createObject(SaleReportSearch::className());
                },
                'dataProvider' => function($params, SaleReportSearch $searchModel) {
                        $dataProvider = $searchModel->search($params);
                        return $dataProvider;
                    },
            ]
        ]);
    }

    /**
     * Lists all SaleReport models.
     * @return mixed
     */
    public function actionIndex()
    {
        /** @var SaleReportSearch $searchModel */
        $searchModel = Yii::createObject(SaleReportSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => $this->getGridColumns($searchModel),
        ]);
    }

    public function getGridColumns(SaleReportSearch $searchModel)
    {
        return [
			'id',
            [
                'label' => 'Файлы',
                'format' => 'raw',
                'value' => function (SaleReport $model) {
                    return $this->renderPartial('@ms/files/attachments/common/views/partial/_files', ['files' => $model->getAttachedFiles()]);
                }
            ],
            [
                'attribute' => 'profile_id',
                'label' => 'ФИО участника',
                'format' => 'raw',
                'filter' => Profile::getOptionsFullName(),
                'titles' => Profile::getOptionsFullName(),
            ],
            [
                'attribute' => 'dealer_id',
                'label' => 'Компания',
                'value' => function(SaleReport $model) {
                    if(!empty($model->profile) && !empty($model->profile->dealer)) {
                        return $model->profile->dealer->name;
                    }

                    return '';
                }
            ],
            [
                'attribute' => 'action_id',
                'filter' => Action::getOptions(),
                'titles' => Action::getOptions(),
            ],
			'created_at:datetime',
        ];
    }

    /**
     * Creates a new SaleReport model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SaleReport;

		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			\Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Record was successfully created'));
			return $this->getCreateUpdateResponse($model);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SaleReport model.
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
     * Deletes an existing SaleReport model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete(array $id)
    {
        $message = is_array($id) ?
            \Yii::t('admin/t', 'Records were successfully deleted') : \Yii::t('admin/t', 'Record was successfully deleted');
        $id = (array)$id;

        foreach ($id as $id_)
            $this->findModel($id_)->delete();

        \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, $message);

        return $this->redirect(['index']);
    }

    /**
     * Finds the SaleReport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SaleReport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SaleReport::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
