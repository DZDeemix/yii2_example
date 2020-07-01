<?php

use yii\helpers\Url;
use yii\grid\GridView;
use modules\sales\common\models\Sale;
use modules\sales\frontend\widgets\assets\SalesHistoryAsset;

/**
 * @var \yii\web\View $this
 * @var bool $allowNewSales
 */
\yz\icons\FontAwesomeAsset::register($this);
SalesHistoryAsset::register($this);
?>

<h1 class="h1-sales" align="center">История покупок</h1>

<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => [
        'class' => 'table-custom',
		'id' => 'sales',
    ],
    'columns' => [
        [
            'label' => false,
            'format' => 'raw',
            'contentOptions' => ['style' => 'width: 100%'],
            'value' => function (Sale $model) {
                return $this->render('@modules/sales/frontend/views/sales/_sale.php', ['model' => $model]);
            }
        ],
    ]
]);
?>

