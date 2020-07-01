<?php

namespace modules\sales\backend\controllers;

use modules\profiles\common\models\Profile;
use modules\sales\backend\models\ProductSearch;
use modules\sales\common\models\Category;
use modules\sales\common\models\Group;
use modules\sales\common\models\Product;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yz\admin\actions\ExportAction;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\traits\CrudTrait;

/**
 * ProductsController implements the CRUD actions for Product model.
 */
class ProductsController extends Controller implements AccessControlInterface
{
    use CrudTrait;

    public function actions()
    {
        return array_merge(parent::actions(), [
            'export' => [
                'class' => ExportAction::className(),
                'dataProvider' => function ($params) {
                    $searchModel = new ProductSearch;
                    $dataProvider = $searchModel->search($params);
                    return $dataProvider;
                },
            ]
        ]);
    }

    /**
     * Lists all Product models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch;
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
            [
                'attribute' => 'id',
                'contentOptions' => ['style' => 'width:70px'],
            ],
//            [
//                'attribute' => 'photo_name',
//                'format' => 'raw',
//                'contentOptions' => ['style' => 'width:160px'],
//                'value' => function (Product $model) {
//                    if (empty($model->photo_name)) {
//                        return '';
//                    }
//
//                    return Html::a(
//                        Html::img($model->photo_url, ['style' => 'max-width:150px; max-height:150px']),
//                        $model->photo_url,
//                        ['target' => '_blank']
//                    );
//                }
//            ],
            [
                'attribute' => 'name',
            ],
            [
                'attribute' => 'title',
                'contentOptions' => ['style' => 'width:200px'],
            ],
            [
                'attribute' => 'bonuses_formula',
                'contentOptions' => ['style' => 'width:200px'],
            ],
            [
                'attribute' => 'role',
                'filter' => Profile::getRoleOptions(),
                'titles' => Profile::getRoleOptions(),
                'contentOptions' => ['style' => 'width:160px;'],
            ],

//            'url:url',
//            'url_shop:url',
//            [
//                'attribute' => 'Цена и вес/объем',
//                'contentOptions' => ['style' => 'width:105px'],
//                'format' => 'raw',
//                'value' => function (Product $model) {
//                    $html = '';
//                    if (!empty($model->price)) {
//                        $html .= $model->price . ' <i class="fa fa-rub"></i>';
//                    }
//                    if (!empty($model->weight)) {
//                        if ($unit = $model->unit) {
//                            $html .= ' / ' . $model->weight . ' ' . $unit->short_name;
//                        }
//                        else {
//                            $html .= ' / ' . $model->weight;
//                        }
//                    }
//                    return $html;
//                }
//            ],
//            [
//                'attribute' => 'bonuses_formula',
//                'label' => 'Формула',
//                'contentOptions' => ['style' => 'width:60px'],
//            ],
            'enabled:boolean',
        ];
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product;

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
     * Updates an existing Product model.
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
     * Deletes an existing Product model.
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
            $model = $this->findModel($id_);
            try {
                $model->delete();
                \Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, $message);
            }
            catch (\Exception $e) {
                $model->updateAttributes(['enabled' => false]);
                \Yii::$app->session->setFlash(\yz\Yz::FLASH_INFO, 'Запись не может быть удалена, так как этот товар фигурирует в Продажах. Товар был отключен из списков.');
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
