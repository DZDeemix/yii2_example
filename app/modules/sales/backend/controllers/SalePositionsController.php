<?php

namespace modules\sales\backend\controllers;

use modules\profiles\common\models\Profile;
use modules\profiles\common\models\Region;
use modules\sales\backend\models\SalePositionSearchWithData;
use modules\sales\common\models\Group;
use modules\sales\common\models\Product;
use modules\sales\common\models\Sale;
use modules\sales\common\sales\statuses\Statuses;
use Yii;
use modules\sales\common\models\SalePosition;
use modules\sales\backend\models\SalePositionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yz\admin\actions\ExportAction;
use yz\admin\grid\columns\DataColumn;
use yz\admin\grid\filters\DateRangeFilter;
use yz\admin\widgets\ActiveForm;
use yz\admin\traits\CheckAccessTrait;
use yz\admin\traits\CrudTrait;
use yz\admin\contracts\AccessControlInterface;

/**
 * SalePositionsController implements the CRUD actions for SalePosition model.
 */
class SalePositionsController extends Controller implements AccessControlInterface
{
    use CrudTrait, CheckAccessTrait;

    public function actions()
    {
        return array_merge(parent::actions(), [
            'export' => [
                'class' => ExportAction::className(),
                'searchModel' => function($params) {
                    /** @var SalePositionSearchWithData $searchModel */
                    return Yii::createObject(SalePositionSearchWithData::className());
                },
                'dataProvider' => function($params, SalePositionSearchWithData $searchModel) {
                        $dataProvider = $searchModel->search($params);
                        return $dataProvider;
                    },
            ]
        ]);
    }

    /**
     * Lists all SalePosition models.
     * @return mixed
     */
    public function actionIndex()
    {
        /** @var SalePositionSearchWithData $searchModel */
        $searchModel = Yii::createObject(SalePositionSearchWithData::className());
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        $dataProvider->setSort([
           'defaultOrder' => ['sale_id' => SORT_DESC, 'id' => SORT_ASC],
        ]);

        $bonuses = $dataProvider->query->sum('sp.bonuses');

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => $this->getGridColumns(),
            'bonuses' => $bonuses,
        ]);
    }

    public function actionExportAll()
    {
        $data = (new \DateTime('now'))->format('d.m.Y');
        Yii::$app->response->sendFile(Yii::getAlias('@data/sale_positions.xlsx'), "sale_positions_$data.xlsx", ['inline' => false])->send();
    }

    public function getGridColumns()
    {
        $isExport = \Yii::$app->request instanceof \yii\console\Request
            || Yii::$app->request->getPathInfo() == 'sales/sale-positions/export';

        if ($isExport) {
            set_time_limit(600);
            ini_set('memory_limit', '-1');
        }

        return [
            [
                'attribute' => 'sale_id',
                'contentOptions' => ['style' => 'width:70px'],
            ],
            [
                'attribute' => 'sale__status',
                'titles' => Sale::getStatusValues(),
                'filter' => Sale::getStatusValues(),
                'labels' => [
                    Statuses::DRAFT => DataColumn::LABEL_DEFAULT,
                    Statuses::ADMIN_REVIEW => DataColumn::LABEL_INFO,
                    Statuses::APPROVED => DataColumn::LABEL_SUCCESS,
                    Statuses::PAID => DataColumn::LABEL_SUCCESS,
                    Statuses::DECLINED => DataColumn::LABEL_DANGER,
                ],
                'contentOptions' => ['style' => 'width:100px;'],
            ],
            [
                'attribute' => 'sale__sold_on',
                'contentOptions' => ['style' => 'text-align:center'],
                'filter' => DateRangeFilter::instance(),
                'value' => function ($model) {
                    return (new \DateTime($model->sale__sold_on))->format('d.m.Y');
                }
            ],
            [
                'attribute' => 'sale__created_at',
                'contentOptions' => ['style' => 'text-align:center'],
                'filter' => DateRangeFilter::instance(),
                'value' => function ($model) {
                    return (new \DateTime($model->sale__created_at))->format('d.m.Y H:i');
                }
            ],
            //'sale__number',
            /*[
                'label' => 'Документы',
                'format' => 'raw',
                'value' => function (SalePosition $model) use ($isExport) {
                    return $isExport
                        ? ''
                        : $this->renderPartial('@modules/sales/backend/views/sales/_documents_export', ['sale' => $model->sale]);
                }
            ],*/
            [
                'attribute' => 'bonuses',
                'contentOptions' => ['style' => 'width:80px;'],
            ],
            [
                'attribute' => 'quantity',
                'contentOptions' => ['style' => 'width:60px;'],
            ],
//            [
//                'attribute' => 'cost',
//                'contentOptions' => ['style' => 'width:60px;'],
//                'value' => function (SalePosition $model) use ($isExport) {
//                    return empty($model->cost) ? '' : $model->cost_real;
//                }
//            ],
            [
                'attribute' => 'product_id',
                'titles' => Product::getOptions(),
                'filter' => Product::getOptions(),
                'contentOptions' => ['style' => 'width:450px; font-size: 12px;'],
            ],
//            [
//                'attribute' => 'group__id',
//                'titles' => Group::getOptions(),
//                'filter' => Group::getOptions(),
//                'contentOptions' => ['style' => 'width:100px;'],
//            ],
//            'sale__place',
            'profile__full_name',
            'profile__phone_mobile',
            'profile__email',
//            [
//                'attribute' => 'profile__checked_at',
//                'value' => function (Sale $model) use ($isExport) {
//                    return empty($model->profile__checked_at)
//                        ? ''
//                        : (new \DateTime($model->profile__checked_at))->format('d.m.Y H:i');
//                }
//            ],
            //'city__title',
//            [
//                'attribute' => 'region__name',
//                'titles' => Region::getNameOptions(),
//                'filter' => Region::getNameOptions(),
//                'contentOptions' => ['style' => 'width:200px;'],
//            ],
        ];
    }

    /**
     * Creates a new SalePosition model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SalePosition;

		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			\Yii::$app->session->setFlash(\yz\Yz::FLASH_SUCCESS, \Yii::t('admin/t', 'Record was successfully created'));
			return $this->getCreateUpdateResponse($model);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SalePosition model.
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
     * Deletes an existing SalePosition model.
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
     * Finds the SalePosition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SalePosition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SalePosition::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
