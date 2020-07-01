<?php

namespace modules\sales\backend\controllers;


use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\jui\DatePicker;
use yii\web\BadRequestHttpException;
use modules\sales\common\models\SalesAction;
use modules\sales\backend\models\SalesActionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yz\admin\actions\ExportAction;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\traits\CrudTrait;
use yz\admin\contracts\AccessControlInterface;
use yz\Yz;

/**
 * SalesActionController implements the CRUD actions for SalesAction model.
 */
class SalesActionController extends Controller implements AccessControlInterface
{
	use CrudTrait, CheckAccessTrait;

	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(), [
			'accessControl' => $this->accessControlBehavior(),
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'delete' => ['post'],
				],
			],
		]);
	}

	public function actions()
	{
		return array_merge(parent::actions(), [
			'export' => [
				'class' => ExportAction::class,
				'searchModel' => function ($params) {
					return Yii::createObject(SalesActionSearch::class);
				},
				'dataProvider' => function ($params, SalesActionSearch $searchModel) {
					return $searchModel->search($params);
				},
			]
		]);
	}

	/**
	 * Lists all SalesAction models.
	 * @return mixed
	 * @throws InvalidConfigException
	 */
	public function actionIndex()
	{
		SalesAction::addActualStatusAction();
		/**
		 * @var SalesActionSearch $searchModel
		 * @var ActiveDataProvider $dataProvider
		 */
		$searchModel = Yii::createObject(SalesActionSearch::class);
		$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
			'columns' => $this->getGridColumns(),
		]);
	}

	public function getGridColumns()
	{
		return [
			'id',
			'action_name',
			[
				'attribute' => 'status',
				'label' => 'Статус акции',
				'format' => 'html',
				'filter' => SalesAction::getStatusAction(),
				'value' => function ($data) {
					if ($data->status == SalesAction::STATUS_SAVE) {
						return "<span style='font-size: 13px;' class='label label-warning'>" . SalesAction::getStatusAction()[$data->status] . "</span>";
					} elseif ($data->status == SalesAction::STATUS_CURRENT) {
						return "<span style='font-size: 13px;' class='label label-success'>" . SalesAction::getStatusAction()[$data->status] . "</span>";
					} else {
						return "<span style='font-size: 13px;' class='label label-danger'>" . SalesAction::getStatusAction()[$data->status] . "</span>";
					}
				}
			],
			[
				'attribute' => 'id',
				'label' => 'Посмотреть участников',
				'format' => 'html',
				'value' => function ($data) {
					return '<p align="center"><a href="/profiles/sales-profile-action/index?action=' . $data->id . '">Участники акции</a></p>';
				}
			],
			[
				'attribute' => 'action_from',
				'format' => ['date', 'php:d.m.Y'],
				'filter' => DatePicker::widget(['language' => 'ru', 'dateFormat' => 'dd.MM.yyyy']),

			],
			[
				'attribute' => 'action_to',
				'format' => ['date', 'php:d.m.Y'],
				'filter' => DatePicker::widget(['language' => 'ru', 'dateFormat' => 'dd.MM.yyyy', 'class' => 'form-control input-sm', 'name' => 'SalesActionSearch[action_from]']),
			],
			'comment'
		];
	}

	/**
	 * Creates a new SalesAction model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 * @throws BadRequestHttpException
	 */
	public function actionCreate()
	{
		$model = new SalesAction;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$model->updateAttributes(['status' => SalesAction::STATUS_SAVE]);
			Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, Yii::t('admin/t', 'Record was successfully created'));
			return $this->getCreateUpdateResponse($model);
		}

		return $this->render('create', compact('model'));
	}

	/**
	 * Updates an existing SalesAction model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException
	 * @throws BadRequestHttpException
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, Yii::t('admin/t', 'Record was successfully updated'));
			return $this->getCreateUpdateResponse($model);
		}
		return $this->render('update', compact('model'));
	}


	/**
	 * Deletes an existing SalesAction model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param $id
	 * @return mixed
	 * @throws NotFoundHttpException
	 * @throws StaleObjectException
	 * @throws \Throwable
	 */
	public function actionDelete($id)
	{
		$message = is_array($id) ?
			Yii::t('admin/t', 'Records were successfully deleted') :
			Yii::t('admin/t', 'Record was successfully deleted');
		$id = (array)$id;

		foreach ($id as $id_)
			$this->findModel($id_)->delete();

		Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, $message);

		return $this->redirect(['index']);
	}

	/**
	 * Старт акции
	 * @param $id
	 * @return string|Response
	 * @throws NotFoundHttpException
	 */
	public function actionStart($id)
	{
		$this->findModel($id)->updateAttributes(['status' => SalesAction::STATUS_CURRENT]);
		Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, 'Акция начата!');
		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * Финиш акции
	 * @param $id
	 * @return Response
	 * @throws NotFoundHttpException
	 */
	public function actionFinish($id)
	{
		$this->findModel($id)->updateAttributes(['status' => SalesAction::STATUS_CLOSE]);
		Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, 'Акция прекращена!');
		return $this->redirect(Yii::$app->request->referrer);
	}

	public function actionSendSms($actionId, string $type)
	{
		$model = $this->findModel($actionId);

		if (count($model->actionProfiles)) {
			foreach ($model->actionProfiles as $actionProfile) {
				if (
					$actionProfile->profile &&
					($type === 'all' || !$actionProfile->is_sms) &&
					Yii::$app->sms->send($actionProfile->profile->phone_mobile, $model->sms_text)
				) {
					$actionProfile->updateAttributes(['is_sms' => true]);
				}
			}
		}

		Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, 'SMS уведомления разосланы');
		return $this->redirect(Yii::$app->request->referrer);
	}

	public function actionSendEmail($actionId, string $type)
	{
		$model = $this->findModel($actionId);

		if (count($model->actionProfiles)) {
			foreach ($model->actionProfiles as $actionProfile) {
				if (
					$actionProfile->profile &&
					($type === 'all' || !$actionProfile->is_email)
				) {
					$send = Yii::$app->mailer->compose(
						'@modules/sales/backend/mail/mail_action',
						['text' => $model->email_text]
					)
						->setSubject($model->email_theme)
						->setTo($actionProfile->profile->email)
						->send();
					if ($send) {
						$actionProfile->updateAttributes(['is_email' => true]);
					}
				}
			}
		}

		Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, 'Email уведомления разосланы');
		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * Finds the SalesAction model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return SalesAction the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	private function findModel($id)
	{
		if (($model = SalesAction::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
