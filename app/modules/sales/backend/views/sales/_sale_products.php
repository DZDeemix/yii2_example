<?php

use modules\sales\common\models\Sale;

/** @var Sale $sale */
$positions = $sale->positions;
?>

<?php if (!empty($positions)): ?>
    <?php foreach ($positions as $position): ?>
        <?php if ($product = $position->product): ?>
			<div style="font-size: 11px;">
                <?= $product->name ?>
			</div>
			<br style="mso-data-placement:same-cell;"/>
        <?php endif; ?>
    <?php endforeach ?>
<?php endif; ?>