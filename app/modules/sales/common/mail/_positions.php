<?php
	/** @var \modules\sales\common\models\Sale $sale */
?>

<table style="width:100%; border-collapse: collapse">
	<tr>
		<td style="border: 1px solid #ddd; padding: 10px 15px">
			Продукция
		</td>
		<td style="border: 1px solid #ddd; padding: 10px 15px">
            <?= $this->renderFile('@modules/sales/backend/views/sales/_positions.php', ['sale' => $sale]); ?>
		</td>
	</tr>
</table>
