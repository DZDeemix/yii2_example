<?php

namespace modules\actions\backend\controllers;

use modules\actions\backend\models\ActionProfileSearch;
use modules\actions\common\models\Action;
use modules\actions\common\models\ActionProfile;
use modules\actions\common\models\ActionProfileByDealer;
use modules\profiles\common\managers\RoleManager;
use modules\profiles\common\models\ProfileDealer;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yz\admin\actions\ExportAction;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\traits\CrudTrait;

/**
 * ActionProfileController implements the CRUD actions for ActionProfile model.
 */
class ActionProfileController extends Controller implements AccessControlInterface
{
    use CrudTrait, CheckAccessTrait;

    public function actions()
    {
        return array_merge(parent::actions(), [
            'export' => [
                'class' => ExportAction::className(),
                'searchModel' => function ($params) {
                    /** @var ActionProfileSearch $searchModel */
                    return Yii::createObject(ActionProfileSearch::className());
                },
                'dataProvider' => function ($params, ActionProfileSearch $searchModel) {
                    $dataProvider = $searchModel->search($params);
                    return $dataProvider;
                },
            ]
        ]);
    }

    /**
     * Lists all ActionProfile models.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        /** @var ActionProfileSearch $searchModel */
        $searchModel = Yii::createObject(ActionProfileSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => $this->getGridColumns($searchModel),
        ]);
    }

    public function getGridColumns(ActionProfileSearch $searchModel)
    {
        return [
            [
                'attribute' => 'profile_id',
                'label' => 'ID участника'
            ],
            [
                'attribute' => 'profileFullname',
                'label' => 'ФИО участника'
            ],
            [
                'attribute' => 'action_id',
                'label' => 'Акция',
                'filter' => Action::getList(),
                'titles' => Action::getList(),
            ],
            [
                'attribute' => 'role',
                'label' => 'Роль участника',
                'filter' => RoleManager::getList(),
                'value' => function ($model) {
                    if ($model->role == RoleManager::ROLE_PROFCLUB) {
                        return RoleManager::getList()[$model->role];
                    }
                    return RoleManager::getList()[$model->role];
                }
            ],

            'last_year_plan',
            'last_year_price_plan'
        ];
    }

    /**
     * Creates a new ActionProfile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionCreate()
    {
        $model = new ActionProfile;

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Record was successfully created'));
            return $this->getCreateUpdateResponse($model);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ActionProfile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\web\BadRequestHttpException
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
     * Deletes an existing ActionProfile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param array $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
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


    public function actionRefresh()
    {
        $message = "обновление участников";

        $rows = (new Query())
            ->select('dealer_profile.profile_id as profile_id, action_id, SUM(action_profile_dealer.last_year_plan) plan,SUM(action_profile_dealer.last_year_price_plan) price_plan')
            ->from(['action_profile_dealer' => ActionProfileByDealer::tableName()])
            ->innerJoin(['dealer_profile' => ProfileDealer::tableName()],
                'dealer_profile.dealer_id=action_profile_dealer.dealer_id ')
            ->innerJoin(['action' => Action::tableName()], 'action.id = action_profile_dealer.action_id')
            ->groupBy('action_id,profile_id')
            ->all();

        $total = count($rows);
        $i = 0;

        foreach ($rows as $row) {
            $model = ActionProfile::findOne(["profile_id"=>$row["profile_id"],"action_id"=>$row["action_id"]]);

            if (is_null($model)) {
                $model = new ActionProfile;
                $total++;
            }

            $model->profile_id = $row["profile_id"];
            $model->action_id = $row["action_id"];
            $model->last_year_plan = $row["plan"];
            $model->last_year_price_plan = $row["price_plan"];
            $model->save();
        }

        \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, "Добавлено записей - ". $total);
        return $this->redirect(['index']);
    }

    /**
     * Finds the ActionProfile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActionProfile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel(
        $id
    ) {
        if (($model = ActionProfile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
