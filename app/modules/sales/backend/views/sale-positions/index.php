<?php

use yii\helpers\Html;
use yz\admin\widgets\Box;
use yz\admin\grid\GridView;
use yz\admin\widgets\ActionButtons;
use yz\icons\Icons;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var modules\sales\backend\models\SalePositionSearch $searchModel
 * @var array $columns
 */

$this->title = modules\sales\common\models\SalePosition::modelTitlePlural();
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;

\modules\sales\backend\assets\FancyBoxAsset::register($this);

# CSS
$css = <<<CSS
	.wrapper {
		overflow-x: scroll !important;
		width: 3100px;
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

<?php $box = Box::begin(['cssClass' => 'sale-position-index box-primary']) ?>
<div class="text-right">
    <?php echo ActionButtons::widget([
        'order' => [/*['search'],*/
            ['export', 'export-all', 'delete', 'return']],
        'gridId' => 'sale-position-grid',
        'searchModel' => $searchModel,
        'modelClass' => 'modules\sales\common\models\SalePosition',
        'buttons' => [
            'export-all' => function () {
                return Html::a(Icons::p('file-excel-o') . 'Быстрый экспорт всех', ['export-all'], [
                    'class' => 'btn btn-default',
                    'title' => 'Быстрый экспорт всех',
                ]);
            },
        ],
    ]) ?>
</div>

<?php //echo $this->render('_search', ['model' => $searchModel]); ?>

<h3>Суммарно бонусов: <?= $bonuses ?></h3>

<?= GridView::widget([
    'id' => 'sale-position-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => array_merge([
        ['class' => 'yii\grid\CheckboxColumn'],
    ], $columns),
]); ?>
<?php Box::end() ?>
