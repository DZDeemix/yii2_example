<?php

use yii\helpers\Html;
use yz\admin\widgets\Box;
use yz\admin\grid\GridView;
use yz\admin\widgets\ActionButtons;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var modules\sales\backend\models\SalesActionSearch $searchModel
 * @var array $columns
 */

$this->title = modules\sales\common\models\SalesAction::modelTitlePlural();
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
$js=<<<JS
    $( document ).ready(function() {
    $('#w0, #w1').addClass('form-control input-sm');
    $('#w0').attr("name","SalesActionSearch[action_from]");
    $('#w1').attr("name","SalesActionSearch[action_to]");
});
JS;
$this->registerJs($js);

?>
<?php $box = Box::begin(['cssClass' => 'sales-action-index box-primary']) ?>
    <div class="text-right">
        <?php echo ActionButtons::widget([
            'order' => [/*['search'],*/ ['export', 'create', 'return']],
            'gridId' => 'sales-action-grid',
            'searchModel' => $searchModel,
            'modelClass' => 'modules\sales\common\models\SalesAction',
        ]) ?>
    </div>


    <?= GridView::widget([
        'id' => 'sales-action-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => array_merge([
            ['class' => 'yii\grid\CheckboxColumn'],
        ], $columns, [
            [
                'class' => 'yz\admin\widgets\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ]),
    ]); ?>
<?php Box::end() ?>
