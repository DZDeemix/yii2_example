<?php

use modules\projects\common\models\Project;
use yii\helpers\Html;
use yz\admin\widgets\Box;
use yz\admin\grid\GridView;
use yz\admin\widgets\ActionButtons;
use yz\icons\Icons;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var modules\projects\backend\models\ProjectSearch $searchModel
 * @var array $columns
 */

$this->title = modules\projects\common\models\Project::modelTitlePlural();
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;

# CSS
$css = <<<CSS
	.purse-balance {
		background: gold;
    	padding: 3px 5px;
    	border-radius: 50%;
    	border: 1px solid orange;
    	box-shadow: 0 0 5px rgba(0,0,0,0.5);
	}
CSS;
$this->registerCss($css);
?>

<?php $box = Box::begin(['cssClass' => 'project-index box-primary']) ?>
<div class="text-right">
    <?php echo ActionButtons::widget([
        'order' => [/*['search'],*/ ['export', 'create', 'delete', 'return']],
        'gridId' => 'project-grid',
        'searchModel' => $searchModel,
        'modelClass' => 'modules\projects\common\models\Project',
    ]) ?>
</div>

<?php //echo $this->render('_search', ['model' => $searchModel]); ?>

<?= GridView::widget([
    'id' => 'project-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => array_merge([
        ['class' => 'yii\grid\CheckboxColumn'],
    ], $columns, [
        [
            'class' => 'yz\admin\widgets\ActionColumn',
            'template' => '{api-settings} {mobile-settings} {taxes-settings} {finances-transactions}'
                . ' {catalog-settings} {cards} {payments-settings} {update} {delete}',
            'buttons' => [
                'finances-transactions' => function ($url, Project $model, $key) {
                    $url = ['/finances/transactions/index', 'ProjectTransactionSearch[purse_id]' => $model->purse->id];
                    return Html::a(Icons::i('dollar-sign'), $url, [
                        'target' => 'blank',
                        'title' => 'Транзакции',
                        'class' => 'btn btn-warning btn-sm',
                        'data-pjax' => '0',
                    ]);
                },
            ]
        ],
    ]),
]); ?>
<?php Box::end() ?>
