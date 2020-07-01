<?php
/** @var \modules\sales\common\models\Sale $model */

use modules\sales\common\models\Sale;

$positions = Sale::getSaleDataTable($model->id);
$history = $model->getOrderedHistory();
?>

<div class="row">
	<div class="col-md-2">
		<div class="data-title">ID продажи</div>
		<div class="center"><?= $model->id ?></div>
	</div>
	<div class="col-md-2">
		<div class="data-title">Дата продажи</div>
		<div class="center"><?= (new \DateTime($model->sold_on))->format('d.m.Y') ?></div>
	</div>
	<div class="col-md-4">
		<div class="data-title">Состав продажи и бонусы</div>
		<div><b>Всего: <?= $model->bonuses ?></b></div>
        <?php foreach ($positions as $position): ?>
            <?= $position['product_name'] ?>
			(<?= $position['product_bonuses'] ?> x <?= $position['product_quantity'] ?>)
        <?php endforeach; ?>
	</div>
	<div class="col-md-4" >
		<div class="data-title">Статус продажи</div>
		<div class="sale-status">
        <?= $model->getStatusText() ?>
        <?php if ($model->userCanEdit()): ?>
			<a href="/sales/sales/edit?id=<?= $model->id ?>" class="btn btn-success" title="Редактировать покупку">
				<i class="fa fa-edit"></i>
			</a>
        <?php endif; ?>
		</div>

		<?php if (!empty($history)): ?>
			<?php foreach ($history as $saleHistory): ?>
				<?php if (!empty($saleHistory->comment)): ?>
					<div class="sale-comment" style="font-size:13px; margin-top: 12px;">
						<div class="sale-comment-from">От администратора: </div>
						<?= $saleHistory->comment ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>
