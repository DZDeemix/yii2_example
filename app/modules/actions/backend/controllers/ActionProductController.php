<?php

namespace modules\actions\backend\controllers;

use modules\actions\backend\models\ActionProductSearch;
use modules\actions\common\models\Action;
use modules\actions\common\models\ActionProduct;
use modules\sales\common\models\Product;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yz\admin\actions\ExportAction;
use yz\admin\actions\ToggleAction;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\traits\CrudTrait;

/**
 * ActionProductController implements the CRUD actions for ActionProduct model.
 */
class ActionProductController extends Controller implements AccessControlInterface
{
    use CrudTrait, CheckAccessTrait;

    public function actions()
    {
        return array_merge(parent::actions(), [
            'toggle' => [
                'class' => ToggleAction::className(),
                'modelClass' => ActionProduct::className(),
                'attributes' => ['active'],
            ],
            'export' => [
                'class' => ExportAction::class,
                'searchModel' => function ($params) {
                    /** @var ActionProductSearch $searchModel */
                    return Yii::createObject(ActionProductSearch::class);
                },
                'dataProvider' => function ($params, ActionProductSearch $searchModel) {
                    $dataProvider = $searchModel->search($params);
                    return $dataProvider;
                },
            ]
        ]);
    }

    /**
     * Lists all ActionProduct models.
     * @return mixed
     */
    public function actionIndex()
    {
        /** @var ActionProductSearch $searchModel */
        $searchModel = Yii::createObject(ActionProductSearch::class);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => $this->getGridColumns($searchModel),
        ]);
    }

    public function getGridColumns(ActionProductSearch $searchModel)
    {
        return [
            'id',
            [
                'attribute' => 'product_id',
                'filter' => Product::getOptions(),
                'titles' => Product::getOptions(),
            ],
            [
                'attribute' => 'action_id',
                'label' => 'Акция',
                'filter' => Action::getList(),
                'titles' => Action::getList(),
            ],
            'bonus'
        ];
    }


    /**
     * Updates an existing ActionProfile model.
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
     * Deletes an existing ActionProfile model.
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
     * Finds the ActionProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActionProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ActionProduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
