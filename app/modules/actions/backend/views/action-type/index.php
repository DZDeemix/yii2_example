<?php

use yii\helpers\Html;
use yz\admin\widgets\Box;
use yz\admin\grid\GridView;
use yz\admin\widgets\ActionButtons;
use yii\bootstrap\Button;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var modules\actions\backend\models\ActionTypeSearch $searchModel
 * @var array $columns
 */

$this->title = modules\actions\common\models\ActionType::modelTitlePlural();
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<?php $box = Box::begin(['cssClass' => 'action-type-index box-primary']) ?>
    <div class="text-right">
        <?php echo ActionButtons::widget([
            'order' => [['export', 'refresh', 'return']],
            'gridId' => 'action-type-grid',
            'searchModel' => $searchModel,
            'modelClass' => 'modules\actions\common\models\ActionType',
            'buttons' => [
                'refresh' => function() {
                    return Button::widget([
                        'tagName' => 'a',
                        'label' => 'Найти и добавить',
                        'encodeLabel' => false,
                        'options' => [
                            'href' => '/actions/action-type/refresh',
                            'class' => 'btn btn-info',
                        ],
                    ]);
                }
            ],
        ]) ?>
    </div>

    <?= GridView::widget([
        'id' => 'action-type-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => array_merge([
            ['class' => 'yii\grid\CheckboxColumn'],
        ], $columns, [
//            [
//                'class' => 'yz\admin\widgets\ActionColumn',
//                'template' => '{update} {delete}',
//            ],
        ]),
    ]); ?>
<?php Box::end() ?>
