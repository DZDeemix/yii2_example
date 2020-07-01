<?php

namespace modules\profiles\backend\controllers;

use marketingsolutions\finance\models\Transaction;
use modules\profiles\backend\models\ProfileTransaction;
use modules\profiles\backend\models\ProfileTransactionSearch;
use modules\profiles\common\models\City;
use modules\profiles\common\models\Profile;
use Yii;
use yii\web\Controller;
use yz\admin\actions\ExportAction;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\grid\columns\DataColumn;
use yz\admin\grid\filters\DateRangeFilter;
use yz\admin\traits\CheckAccessTrait;

/**
 * Class ProfileTransactionsController
 */
class ProfileTransactionsController extends Controller implements AccessControlInterface
{
    use CheckAccessTrait;

    public function actions()
    {
        return array_merge(parent::actions(), [
            'export' => [
                'class' => ExportAction::className(),
                'dataProvider' => function ($params) {
                    $searchModel = Yii::createObject(ProfileTransactionSearch::class);
                    $dataProvider = $searchModel->search($params);
                    return $dataProvider;
                },
            ]
        ]);
    }

    public function actionIndex()
    {
        /** @var ProfileTransactionSearch $searchModel */
        $searchModel = Yii::createObject(ProfileTransactionSearch::class);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

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
            [
                'attribute' => 'type',
                'filter' => Transaction::getTypeValues(),
                'titles' => Transaction::getTypeValues(),
                'labels' => [
                    Transaction::INCOMING => DataColumn::LABEL_SUCCESS,
                    Transaction::OUTBOUND => DataColumn::LABEL_DANGER,
                ]
            ],
            'profile__full_name',
            'profile__phone_mobile',
            'amount',
            'balance_before',
            'balance_after',
            'title',
            'comment',
            [
                'attribute' => 'created_at',
                'contentOptions' => ['style' => 'text-align:center; width: 180px;'],
                'filter' => DateRangeFilter::instance(),
                'value' => function (ProfileTransaction $model) {
                    return (new \DateTime($model->created_at))->format('d.m.Y H:i');
                }
            ],
            [
                'attribute' => 'profile__city_id',
                'filter' => City::getOptions(),
                'titles' => City::getOptions(),

            ],

            [
                'attribute' => 'profile__role',
                'filter' => Profile::getRoleOptions(),
                'titles' => Profile::getRoleOptions(),
                'contentOptions' => ['style' => 'width:160px;'],
            ],
        ];
    }
}