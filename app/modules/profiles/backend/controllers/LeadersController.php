<?php

namespace modules\profiles\backend\controllers;

use modules\profiles\common\models\City;
use modules\profiles\common\models\LegalPerson;
use modules\projects\common\models\Project;
use ms\loyalty\location\common\models\District;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yz\admin\actions\ExportAction;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\traits\CrudTrait;
use yz\admin\contracts\AccessControlInterface;
use modules\profiles\common\models\Region;
use modules\profiles\common\models\Leader;
use modules\profiles\backend\forms\LeaderForm;
use modules\profiles\backend\models\LeaderSearch;
use modules\profiles\backend\rbac\Rbac;

/**
 * LeaderController implements the CRUD actions for Leader model.
 */
class LeadersController extends Controller implements AccessControlInterface
{
    use CrudTrait, CheckAccessTrait;

    public function actions()
    {
        return array_merge(parent::actions(), [
            'export' => [
                'class' => ExportAction::class,
                'searchModel' => function($params) {
                    /** @var LeaderSearch $searchModel */
                    return Yii::createObject(LeaderSearch::class);
                },
                'dataProvider' => function($params, LeaderSearch $searchModel) {
                        $dataProvider = $searchModel->search($params);
                        return $dataProvider;
                },
            ]
        ]);
    }

    /**
     * Lists all Leader models.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        /** @var LeaderSearch $searchModel */
        $searchModel = Yii::createObject(LeaderSearch::class);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => $this->getGridColumns($searchModel),
        ]);
    }

    public function getGridColumns(LeaderSearch $searchModel)
    {
        return [
			'id',
            'full_name',
            [
                'attribute' => 'role',
                'filter' => Rbac::getRolesList(),
                'titles' => Rbac::getRolesList(),
            ],
            [
                'attribute' => 'legal_person_id',
                'label' => 'Юрлицо',
                'filter' => Project::getTitleOptions(),
                'titles' => Project::getTitleOptions(),
            ],

            [
                'attribute' => 'login',
                'value' => function (Leader $model) {
                    return $model->adminUser->login ?? null;
                }
            ],
			'phone_mobile',
			'email:email',
            'created_at:datetime'
        ];
    }

    /**
     * Creates a new Leader model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionCreate()
    {
        $model = new LeaderForm;

        if ($model->load(Yii::$app->request->post()) && $model->process()) {
            Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, Yii::t('admin/t', 'Record was successfully created'));

            return $this->getCreateUpdateResponse($model);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Leader model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->process()) {
			Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, Yii::t('admin/t', 'Record was successfully updated'));

			return $this->getCreateUpdateResponse($model);
		}

        return $this->render('update', [
            'model' => $model,
        ]);
	}

    /**
     * Deletes an existing Leader model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param array $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(array $id)
    {
        $message = is_array($id)
            ? Yii::t('admin/t', 'Records were successfully deleted')
            : Yii::t('admin/t', 'Record was successfully deleted');

        $id = (array)$id;

        foreach ($id as $id_) {
            $this->findModel($id_)->delete();
        }

        Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, $message);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Leader model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Leader the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = LeaderForm::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}
