<?php

/* @var $this yii\web\View */
/* @var $model modules\sales\common\models\Sale */
/* @var $profile \modules\profiles\common\models\Profile */

$this->title = 'Редактировать покупку';
$this->params['header'] = $this->title;
$this->params['breadcrumbs'][] = ['label' => 'Покупки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
	.sale-history {
		font-size: 14px;
		border-bottom: 1px solid #ddd;
		padding-bottom: 10px;
		margin-bottom: 10px;
	}
	h3 {
		margin: 0 0 15px;
	}
	.row-add-comment {
		border-bottom: 1px dashed #ddd;
		text-align: center;
		margin-bottom: 20px;
	}
	.row-add-comment > button {
		margin: 0 0 15px !important;
	}
CSS;
$this->registerCss($css);
?>

<h2 style="text-align: center; margin: 0 0 20px">
	Редактирование покупки №<?= $model->id ?>
</h2>

<div class="row">
	<div class="col-md-7">
        <?= \modules\sales\frontend\widgets\EditSaleWidget::widget([
            'sale' => $model
        ]) ?>
	</div>
	<div class="col-md-5" style="padding-left: 30px; border-left: 1px solid #ddd">
		<h3 class="center">История и комментарии</h3>
        <?= \modules\sales\frontend\widgets\SaleHistoryWidget::widget([
            'sale' => $model,
            'redirectSuccess' => '/sales/sales/edit?id=' . $model->id,
        ]) ?>
	</div>
</div>