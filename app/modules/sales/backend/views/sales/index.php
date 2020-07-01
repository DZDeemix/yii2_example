<?php

use modules\sales\common\models\Sale;
use modules\sales\common\sales\statuses\Statuses;
use yii\helpers\Html;
use yz\admin\grid\GridView;
use yz\admin\widgets\ActionButtons;
use yz\admin\widgets\Box;
use yz\icons\Icons;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var modules\sales\backend\models\SaleSearch $searchModel
 * @var array $columns
 * @var integer $bonuses
 */

$this->title = modules\sales\common\models\Sale::modelTitlePlural();
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;

\modules\sales\backend\assets\FancyBoxAsset::register($this);

# CSS
$css = <<<CSS
	.wrapper {
		overflow-x: scroll !important;
		width: 3900px;
	}
CSS;
$this->registerCss($css);

# JS
$js = <<<JS
	jQuery(document).ready(function() {
		jQuery('.fancybox').fancybox({
        	autoSize    : false,
        	width       : '100%',
        	height      : '100%',
        	helpers : {
    			title: {
      				type: 'inside',
      				position: 'top'
    			}
  			},
  			beforeLoad : function() {
     			this.title = '<a class="btn btn-default" href="' + this.title + '" target="_blank">'
     				+ '<i class="fa fa-external-link-square" style="color:red"></i> ' + this.title + '</a>';
    		}
		});
		jQuery('.fancybox-pdf').fancybox({
    		type   : 'iframe',
        	autoSize    : false,
        	width       : '100%',
        	height      : '100%',
        	helpers : {
    			title: {
      				type: 'inside',
      				position: 'top'
    			}
  			},
  			beforeLoad : function() {
     			this.title = '<a class="btn btn-default" href="' + this.title + '" target="_blank">'
     				+ '<i class="fa fa-external-link-square" style="color:red"></i> ' + this.title + '</a>';
    		}
		});
	});
JS;
$this->registerJs($js);
?>

<?php $box = Box::begin(['cssClass' => 'sale-index box-primary']) ?>
<div class="text-right">
    <?php echo ActionButtons::widget([
        'order' => [['export', 'approve', 'delete', 'return']],
        'gridId' => 'sale-grid',
        'searchModel' => $searchModel,
        'modelClass' => 'modules\sales\common\models\Sale',
        'buttons' => [
            'export-all' => function () {
                return Html::a(Icons::p('file-excel-o') . 'Быстрый экспорт всех', ['export-all'], [
                    'class' => 'btn btn-default',
                    'title' => 'Быстрый экспорт всех',
                ]);
            },
            'approve' =>   $searchModel->statusManager->adminCanSetStatus(Statuses::PAID) ? function () {
                return \yii\bootstrap\ButtonDropdown::widget([
                    'tagName' => 'span',
                    'label' => 'Изменить статус у выбранных',
                    'encodeLabel' => false,
                    'split' => false,
                    'dropdown' => [
                        'encodeLabels' => false,
                        'items' => [
                            [
                                'label' => 'Одобрить выбранные',
                                'url' => ['change-status-selected', 'status' => Statuses::APPROVED],
                                'linkOptions' => [
                                    'data' => [
                                        'grid' => 'sale-grid',
                                        'grid-bind' => 'selection',
                                        'grid-param' => 'ids',
                                        'confirm' => 'Вы уверены, что одобрить выбранные записи?',
                                    ],
                                ]
                            ],
                            [
                                'label' => 'Начислить баллы',
                                'url' => ['change-status-selected', 'status' => Statuses::PAID],
                                'linkOptions' => [
                                    'data' => [
                                        'grid' => 'sale-grid',
                                        'grid-bind' => 'selection',
                                        'grid-param' => 'ids',
                                        'confirm' => 'Вы уверены, что одобрить выбранные записи?',
                                    ],
                                ]
                            ]
                        ]
                    ],
                    'options' => [
                        'class' => 'btn btn-default',
                    ],
                ]);
            }:function () {
                return \yii\bootstrap\ButtonDropdown::widget([
                    'tagName' => 'span',
                    'label' => 'Изменить статус у выбранных',
                    'encodeLabel' => false,
                    'split' => false,
                    'dropdown' => [
                        'encodeLabels' => false,
                        'items' => [
                            [
                                'label' => 'Одобрить выбранные',
                                'url' => ['change-status-selected', 'status' => Statuses::APPROVED],
                                'linkOptions' => [
                                    'data' => [
                                        'grid' => 'sale-grid',
                                        'grid-bind' => 'selection',
                                        'grid-param' => 'ids',
                                        'confirm' => 'Вы уверены, что одобрить выбранные записи?',
                                    ],
                                ]
                            ]
                        ]
                    ],
                    'options' => [
                        'class' => 'btn btn-default',
                    ],
                ]);
            }
        ]
    ]) ?>
</div>

<h3>Суммарно бонусов: <?= $bonuses ?></h3>

<?= GridView::widget([
    'id' => 'sale-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => array_merge([
        ['class' => 'yii\grid\CheckboxColumn'],
    ], [
        [
            'class' => 'yz\admin\widgets\ActionColumn',
            'template' => '{pay} {view} {delete}',
            'buttons' => [
            	'pay' => function ($url, Sale $model, $key) {
					if ($model->status != 'approved') {
						return '';
					}

					$url = ['/sales/sales/change-status', 'id' => $model->id, 'status' => Statuses::PAID];

                    return Html::a(Icons::i('usd'), $url, [
                        'title' => 'Зачислить бонусы по одобренной покупке',
                        'data-confirm' => 'Вы уверены, что хотите зачислить бонусы?',
                        'data-method' => 'post',
                        'class' => 'btn btn-success btn-sm',
                        'data-pjax' => '0',
                    ]);
                },
                'delete' => function ($url, Sale $model, $key) {
                    if ($model->statusManager->canBeDeleted() == false) {
                        return '';
                    }

                    return Html::a(Icons::i('trash-o fa-lg'), $url, [
                        'title' => Yii::t('admin/t', 'Delete'),
                        'data-confirm' => Yii::t('admin/t', 'Are you sure to delete this item?'),
                        'data-method' => 'post',
                        'class' => 'btn btn-danger btn-sm',
                        'data-pjax' => '0',
                    ]);
                }
            ]
        ],
    ], $columns),
]); ?>
<?php Box::end() ?>

