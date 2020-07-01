<?php

use yz\admin\grid\GridView;
use yz\admin\widgets\ActionButtons;
use yz\admin\widgets\Box;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \modules\profiles\backend\models\ProjectProfileTransactionSearch $searchModel
 * @var array $columns
 */

$this->title = 'Транзакции бонусных баллов';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<?php $box = Box::begin(['cssClass' => 'profile-index box-primary']) ?>
    <div class="text-right">
        <?php echo ActionButtons::widget([
            'order' => [['export', 'return']],
            'gridId' => 'profile-transactions-grid',
            'searchModel' => $searchModel,
            'modelClass' => \modules\profiles\backend\models\ProfileTransaction::class,
        ]) ?>
    </div>

    <?= GridView::widget([
        'id' => 'profile-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => array_merge([

        ], $columns, [

        ]),
    ]); ?>
<?php Box::end() ?>
