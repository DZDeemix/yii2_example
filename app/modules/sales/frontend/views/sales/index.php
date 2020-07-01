<?php

use modules\sales\common\models\Sale;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yz\icons\Icons;

/* @var $this yii\web\View */
/* @var $searchModel modules\sales\frontend\models\SaleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Покупки';
$this->params['header'] = 'Покупки';
$this->params['breadcrumbs'][] = $this->title;

\yz\icons\FontAwesomeAsset::register($this);
?>
<p>
    <a class="btn btn-success" href="<?= Url::to(['sales/app']) ?>"><i class="fa fa-plus"></i> Добавить покупку</a>
</p>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
//                'filterModel' => $searchModel,
    'columns' => [
        'id',
        [
            'attribute' => 'status',
            'value' => function (Sale $data) {
                return Sale::getStatusValues()[$data->status];
            }
        ],
        'bonuses',
        'sold_on:date',
        'created_at:datetime',

        [
            'class' => 'yii\grid\ActionColumn',
            //'template' => '<nobr>{view} {update} {delete}</nobr>',
            'template' => '<nobr>{view} {update}</nobr>',
            'buttons' => [
                'view' => function ($url, Sale $model, $key) {
                    return Html::a(Icons::p('eye'), ['view', 'id' => $model->id], ['class' => 'btn btn-success']);
                },
                'update' => function ($url, Sale $model, $key) {
                    if ($model->statusManager->recipientCanEdit()) {
                        return Html::a(Icons::p('pencil-square-o'), ['sales/app', 'id' => $model->id], ['class' => 'btn btn-success']);
                    } else {
                        return '';
                    }
                },
//                'delete' => function ($url, Sale $model, $key) {
//                    if ($model->statusManager->canBeDeleted()) {
//                        return Html::a(Icons::p('trash-o'), ['delete', 'id' => $model->id], [
//                            'class' => 'btn btn-danger',
//                            'data' => [
//                                'confirm' => 'Вы уверены, что хотите удалить заявку?',
//                                'method' => 'post',
//                            ]
//                        ]);
//                    } else {
//                        return '';
//                    }
//                }
            ]
        ],
    ]
]); ?>
