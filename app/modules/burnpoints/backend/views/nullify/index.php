<?php

use modules\burnpoints\backend\models\BurnPointWithData;
use modules\burnpoints\common\models\BurnPoint;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yz\admin\grid\GridView;
use yz\admin\widgets\ActionButtons;
use yz\admin\widgets\Box;

/**
 * @var View $this
 * @var BurnPointWithData $searchModel
 * @var ActiveDataProvider $dataProvider
 * @var array $columns
 */

$this->title = BurnPoint::modelTitlePlural();
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;

$box = Box ::begin(['cssClass' => 'burnpoints-nullify-index box-primary'])
?>

<div class="text-right">
    <?= ActionButtons::widget([
        'order' => [['export', 'return']],
        'gridId' => 'burnpoints-nullify-grid',
        'searchModel' => $searchModel,
        'modelClass' => BurnPoint::class,
    ]) ?>
</div>

<?= GridView::widget([
    'id' => 'burnpoints-nullify-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $columns
]); ?>

<?php Box::end();
