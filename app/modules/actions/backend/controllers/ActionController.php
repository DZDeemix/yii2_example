<?php

namespace modules\actions\backend\controllers;


use modules\actions\backend\forms\ActionForm;
use modules\actions\backend\models\ActionSearch;
use modules\actions\common\models\Action;
use modules\actions\common\models\ActionType;
use modules\actions\common\types\PlanCompletePersonalActionByAmountType;
use modules\actions\common\types\PlanCompletePersonalActionByPriceType;
use modules\actions\common\types\PlanCompletePersonalActionType;
use modules\profiles\common\managers\RoleManager;
use modules\profiles\common\models\City;
use modules\profiles\common\models\Leader;
use modules\profiles\common\models\Profile;
use modules\profiles\common\models\Region;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yz\admin\actions\ExportAction;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\traits\CrudTrait;
use yz\Yz;

class ActionController extends Controller implements AccessControlInterface
{
    use CrudTrait, CheckAccessTrait;

    public function actions()
    {
        return array_merge(parent::actions(), [
            'export' => [
                'class' => ExportAction::class,
                'searchModel' => function ($params) {
                    /** @var ActionSearch $searchModel */
                    return Yii::createObject(ActionSearch::class);
                },
                'dataProvider' => function ($params, ActionSearch $searchModel) {
                    $dataProvider = $searchModel->search($params);
                    return $dataProvider;
                },
            ]
        ]);
    }

    /**
     * Lists all Action models.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        /** @var ActionSearch $searchModel */
        $searchModel = Yii::createObject(ActionSearch::class);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => $this->getGridColumns($searchModel),
        ]);
    }

    public function getGridColumns(ActionSearch $searchModel)
    {
        return [
            'id',
            'start_on:date',
            'end_on:date',
            [
                'attribute' => 'admin_id',
                'label' => 'Создал',
                'value' => function (ActionSearch $model) {
                    return $model->admin ? $model->admin->name : '';
                }
            ],
            [
                'attribute' => 'role',
                'filter' =>  Profile::getRoleOptions(),
                'titles' =>  Profile::getRoleOptions(),
            ],
            /*[
                'attribute' => 'type_id',
                'filter' => ActionType::getList(),
                'titles' => ActionType::getList(),
            ],*/
            'title',
            // 'short_description',
           /* [
                'attribute' => 'bonuses_formula',
                'format' => 'html',
                'value' => function (ActionSearch $model) {
                    return $model->bonuses_formula . " " . Html::a('(...)',
                            Url::to('/actions/action-product/index?ActionProductSearch[action_id]=' . $model->id), [
                                "title" => "Задать баллы по каждой позиции индивидуально"
                            ]);
                }
            ],*/
            //'bonuses_amount',
            //'plan_amount',
           /* [
                'attribute' => 'pay_type',
                'filter' => Action::getPayTypesList(),
                'titles' => Action::getPayTypesList(),
                'value' => function (ActionSearch $model) {
                    $title = Action::getPayTypesList()[$model->pay_type];
                    if (Action::PAY_TYPE_THRESHOLD === $model->pay_type) {
                        $title .= " ($model->pay_threshold)";
                    }
                    return $title;
                }
            ],*/
            [
                'attribute' => 'status',
                'filter' => Action::getStatusesList(),
                'format' => 'raw',
                'value' => function (ActionSearch $model) {
                    return $this->renderPartial('_statuses', ['model' => $model]);
                }
            ],
            /*[
                'attribute' => 'id',
                'label' => 'Загрузка планов из XLS',
                'format' => 'html',
                'value' => function ($model) {
                    $actionType = ActionType::findOne($model->type_id);
                    if ($model->status != Action::STATUS_FINISHED && $actionType && in_array($actionType->className, [
                            PlanCompletePersonalActionType::class,

                        ])) {
                        return Html::a('<img src="/images/xls_icon.png">',
                            Url::to('/actions/download-actions-user?id=' . $model->id));
                    }
                    if ($model->status != Action::STATUS_FINISHED && $actionType && in_array($actionType->className, [

                            PlanCompletePersonalActionByPriceType::class,
                            PlanCompletePersonalActionByAmountType::class
                        ])) {
                        return Html::a('<img src="/images/xls_icon.png">',
                            Url::to('/actions/download-actions-dealer?id=' . $model->id));
                    }
                    return "";
                }
            ],*/
           /* [
                'attribute' => 'olap',
                'label' => 'Сверка данных  из ОЛАП (XLS)',
                'format' => 'html',
                'value' => function ($model) {
                    $actionType = ActionType::findOne($model->type_id);
                    if ($model->status != Action::STATUS_FINISHED) {
                        return Html::a('<img src="/images/olap.png">',
                            Url::to('/sales/import/olap-sale/?action_id=' . $model->id));
                    }
                    return "";
                }
            ],
            [
                'label' => 'Планы/участники (для акций с индивидуальными планами)',
                'format' => 'html',
                'value' => function ($model) {
                    $actionType = ActionType::findOne($model->type_id);

                    if ($actionType && in_array($actionType->className, [
                                PlanCompletePersonalActionType::class,
                                PlanCompletePersonalActionByPriceType::class,
                                PlanCompletePersonalActionByAmountType::class
                            ]
                        )) {
                        return '<a class="btn btn-success" href="/actions/action-profile/index?action=' . $model->id . '">Просмотр</a>';
                    }

                    return '';
                }
            ],
            [
                'label' => 'Список участников',
                'format' => 'html',
                'value' => function ($model) {
                    return '<a class="btn btn-success" href="/actions/action-participant/index?ActionParticipantSearch[is_participant]=1&ActionParticipantSearch[action__id]=' . $model->id . '">Просмотр (' . $model->getActionParticipantsCount() . ')</a>';
                }
            ],*/
            'created_at:datetime',
            'confirm_period',
           /* [
                'attribute' => 'olap_period',
                'format' => 'html',
                'value' => function (ActionSearch $model) {
                    return date("m.Y", strtotime($model->olap_period));

                }
            ],*/
            [
                'attribute' => 'email_is_send',
                'format' => 'html',
                'value' => function (ActionSearch $model) {
                    return $model->email_is_send
                        ? Html::label('Да', [
                            'class' => 'btn btn-xs btn-success',
                        ])
                        : 'Нет';
                }
            ],


        ];
    }


    /**
     * Creates a new Action model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $forRole
     * @return mixed
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionCreate()
    {
        $model = new ActionForm();
        $model->has_products = true;
        $model->status = ActionForm::STATUS_NEW;

        if ($model->load(Yii::$app->request->post()) && $model->process()) {
            Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, 'Акция успешно создана');

            return $this->getCreateUpdateResponse($model);
        }

        $leader = Leader::getLeaderByIdentity();

        return $this->render('create', [
            'model' => $model,
            'actionTypes' => ActionType::find()->all(),
            'regions' => Region::getOptions($leader),
            'cities' => City::getTitleOptions($leader),
            'profiles' => Profile::getOptions($leader),
        ]);
    }

    /**
     * Updates an existing Action model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws \yii\web\BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findAction($id);

        if ($model->load(Yii::$app->request->post()) && $model->process()) {
            Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, 'Акция успешно изменена');

            return $this->getCreateUpdateResponse($model);
        }

        $leader = Leader::getLeaderByIdentity();

        return $this->render('update', [
            'model' => $model,
            'actionTypes' => ActionType::find()->all(),
            'regions' => Region::getOptions($leader),
            'cities' => City::getTitleOptions($leader),
            'profiles' => Profile::getOptions($leader),
        ]);
    }

    /**
     * Deletes an existing Action model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id)
    {
        $model = $this->findAction($id);

        if ($model->statusManager->canBeDeleted()) {
            $model->delete();
            Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, "Акция успешно удалена");
        } else {
            Yii::$app->session->setFlash(Yz::FLASH_WARNING,
                "Невозможно удалить акцию, так как акция завершена или в акцию уже добавлены продажи");
        }

        return $this->redirect(['index']);
    }

    public function actionSetStatus(int $id, string $value)
    {
        $model = $this->findAction($id);
        $model->status = $value;

        if (false === isset(Action::getStatusesList()[$value])) {
            Yii::$app->session->setFlash(Yz::FLASH_WARNING, "Неверное значение статуса");

            return $this->redirect('index');
        }

        $result = false;

        if ($model->statusManager->canChangeStatus()) {
            $result = $model->updateAttributes(['status' => $value]);
        }

        $result
            ? Yii::$app->session->setFlash(Yz::FLASH_SUCCESS, "Статус акции успешно изменен")
            : Yii::$app->session->setFlash(Yz::FLASH_WARNING, "Не удалось изменить статус акции");

        return $this->redirect('index');
    }

    /**
     * @param $id
     * @return ActionForm
     * @throws NotFoundHttpException
     */
    protected function findAction($id)
    {
        if (($model = ActionForm::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Отправка приглашений по E-mail
     * @param $action_id
     * @return Response
     */
    public function actionEmailSend($action_id)
    {
        if ($action_id && Action::sendEmail($action_id)) {
            \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS,
                \Yii::t('admin/t', 'Уведомления по Email поставлены в очередь на отправку'));
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            \Yii::$app->session->setFlash(\yz\Yz::FLASH_WARNING, \Yii::t('admin/t', 'Уведомления не отправлены'));
            return $this->redirect(Yii::$app->request->referrer);
        }
    }


}
