<?php

namespace modules\profiles\backend\controllers;

use modules\profiles\backend\models\ProfileSearch;
use modules\profiles\common\models\City;
use modules\profiles\common\models\Profile;
use modules\projects\backend\utils\MultipurseColumn;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yz\admin\actions\ExportAction;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\grid\filters\BooleanFilter;
use yz\admin\grid\filters\DateRangeFilter;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\traits\CrudTrait;
use yii\helpers\Html;
use yz\icons\Icons;

/**
 * ProfilesController implements the CRUD actions for Profile model.
 */
class ProfilesController extends Controller implements AccessControlInterface
{
    use CrudTrait, CheckAccessTrait;

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function actions()
    {
        return array_merge(parent::actions(), [
            'export' => [
                'class' => ExportAction::className(),
                'dataProvider' => function ($params) {
                    $searchModel = Yii::createObject(ProfileSearch::class);
                    $dataProvider = $searchModel->search($params);
                    return $dataProvider;
                },
            ],
            'export-audience' => [
                'class' => ExportAction::className(),
                'dataProvider' => function ($params) {
                    $searchModel = Yii::createObject(ProfileSearch::class);
                    $dataProvider = $searchModel->search($params);
                    return $dataProvider;
                },
            ]
        ]);
    }

    /**
     * Lists all Profile models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        /** @var ProfileSearch $searchModel */
        $searchModel = Yii::createObject(ProfileSearch::class);
        /** @var ActiveDataProvider $dataProvider */
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
        $isExport = \Yii::$app->request instanceof \yii\console\Request
            || Yii::$app->request->getPathInfo() == 'profiles/profiles/export';

        if ($isExport) {
            set_time_limit(600);
            ini_set('memory_limit', '-1');
        }

        return [
            [
                'attribute' => 'id',
                'contentOptions' => ['style' => 'width:100px;'],
            ],
            [
                'attribute' => 'full_name',
                'contentOptions' => ['style' => 'width:160px;'],
            ],
            'phone_mobile',
            [
                'attribute' => 'role',
                'filter' => Profile::getRoleOptions(),
                'titles' => Profile::getRoleOptions(),
                'contentOptions' => ['style' => 'width:160px;'],
            ],
            'email',
            [
                'attribute' => 'city_id',
                'filter' => City::getOptions(),
                'headerOptions' => ['style' => 'width:30%'],
                'value' => function (Profile $model) {
                    return $model->city->title ?? null;
                }
            ],
            'dealer__name',
            'social_link',
            'company_name',
            [
                'attribute' => 'is_checked',
                'label' => 'Подтвержден',
                'format' => 'raw',
                'filter' => [
                    0 => 'нет',
                    1 => 'да'
                ],
                'value' => function (Profile $model) {
                    return $model->is_checked
                        ? Html::a('Да', ['/profiles/profiles/confirm', 'id' => $model->id, 'value' => false], [
                            'class' => 'btn btn-xs btn-success',
                            'title' => 'Отменить подтверждение участника'
                        ])
                        : Html::a('Нет', ['/profiles/profiles/confirm', 'id' => $model->id, 'value' => true], [
                            'class' => 'btn btn-xs btn-default',
                            'title' => 'Подтвердить участника'
                        ]);
                }
            ],
            [
                'attribute' => 'created_at',
                'filter' => DateRangeFilter::instance(),
                'contentOptions' => ['style' => 'width:170px; text-align:center;'],
                'format' => 'raw',
                'value' => function (Profile $model) {
                    return (new \DateTime($model->created_at))->format('d.m.Y H:i');
                }
            ],
            [
                'attribute' => 'registered_at',
                'filter' => DateRangeFilter::instance(),
                'contentOptions' => ['style' => 'width:170px; text-align:center;'],
                'format' => 'raw',
                'value' => function (Profile $model) {
                    return empty($model->registered_at)
                        ? "<span class='label label-default'>нет</span>"
                        : (new \DateTime($model->registered_at))->format('d.m.Y H:i');
                }
            ],
            [
                'attribute' => 'blocked_at',
                'filter' => BooleanFilter::instance(),
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:140px; text-align:center'],
                'value' => function (Profile $model) {
                    if ($model->blocked_at) {
                        $date = (new \DateTime($model->blocked_at))->format('d.m.Y H:i');
                        return "<span class='label label-danger'>$date</span><div style='font-size:11px'>{$model->blocked_reason}</div>";
                    }
                    else {
                        return "<span class='label label-default'>нет</span>";
                    }
                }
            ],
            [
                'attribute' => 'accountProfile__validated_at',
                'contentOptions' => ['style' => 'width:170px; text-align:center;'],
                'format' => 'raw',
                'value' => function (Profile $model) {
                    return empty($model->accountProfile__validated_at)
                        ? "<span class='label label-default'>нет</span>"
                        : (new \DateTime($model->accountProfile__validated_at))->format('d.m.Y H:i');
                }
            ],
            MultipurseColumn::get(),
            //            [
            //                'label' => 'Ручное начисление баллов',
            //                'format' => 'raw',
            //                'contentOptions' => ['style' => 'text-align:center'],
            //                'value' => function (Profile $model) {
            //                    return Html::a(Icons::i('ruble-sign'), ['/manual/manage-bonuses/index', 'id' => $model->id], [
            //                        'class' => 'btn btn-default btn-sm',
            //                        'title' => 'Изменить баланс участника',
            //                    ]);
            //                }
            //            ],
            //'is_uploaded:boolean',
        ];
    }

    public function actionConfirm(int $id, int $value)
    {
        $model = $this->findModel($id);

        if ($value) {
            $transaction = \Yii::$app->db->beginTransaction();
            if (empty($model->email_confirmed_at)) {
                try {
                    Yii::$app->mailer->compose('@modules/profiles/common/mail/confirm.php', [
                        'name' => $model->first_name,
                    ])
                        ->setSubject('Bonus Club PRO. Ваша регистрация подтверждена!')
                        ->setTo($model->email)
                        ->send();
                    $model->confirmEmail();
                    
                }
                catch (\Exception $e) {
                    $transaction->rollBack();
                }
            }
            $model->updateAttributes([
                'checked_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                'is_checked' => true,
            ]);
            $transaction->commit();

        }
        else {
            $model->updateAttributes([
                'checked_at' => new Expression('NUll'),
                'is_checked' => false,
            ]);
        }

        return $this->goPreviousUrl();
    }

    /**
     * Creates a new Profile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Profile;

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Record was successfully created'));
            return $this->getCreateUpdateResponse($model);
        }
        else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Profile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
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
        else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Profile::findOne($id)) !== null) {
            return $model;
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Deletes an existing Profile model.
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

        Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, $message);

        return $this->redirect(['index']);
    }

    public function actionBan($id)
    {
        $model = $this->findModel($id);
        $reason = Yii::$app->request->post('reason');
        $model->ban($reason);

        Yii::$app->session->setFlash(\yz\Yz::FLASH_INFO, 'Участник забанен и теперь не может участвовать в программе');

        return $this->goPreviousUrl();
    }

    public function actionUnban($id)
    {
        $model = $this->findModel($id);
        $model->unban();

        Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, 'Участник разбанен и теперь может участвовать в программе');

        return $this->goPreviousUrl();
    }

    public function actionBlock($id)
    {
        $model = $this->findModel($id);
        $reason = Yii::$app->request->post('reason');
        $model->block($reason);

        Yii::$app->session->setFlash(\yz\Yz::FLASH_INFO, 'Участник заблокирован и теперь не может тратить свои баллы');

        return $this->goPreviousUrl();
    }

    public function actionUnblock($id)
    {
        $model = $this->findModel($id);
        $model->unblock();

        Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, 'Участник разблокирован и теперь может тратить свои баллы');

        return $this->goPreviousUrl();
    }

    public function actionLogin($id)
    {
        $model = $this->findModel($id);

        $updated = false;
        if (empty($model->external_id)) {
            $model->external_id = $model->id;
            $updated = true;
        }
        if (empty($model->external_token)) {
            $model->external_token = Yii::$app->security->generateRandomString();
            $updated = true;
        }

        if ($updated) {
            $model->save(false);
            $model->refresh();
        }

        $url = ($_ENV['FRONTEND_SPA'] ?? null) . "/login-external/{$model->external_id}/{$model->external_token}";

        return $this->redirect($url);
    }
}
