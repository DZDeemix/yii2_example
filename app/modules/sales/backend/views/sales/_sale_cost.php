<?php

use modules\sales\common\models\Sale;

/** @var Sale $sale */
$positions = $sale->positions;
?>

<?php if (!empty($positions)): ?>
    <?php foreach ($positions as $position): ?>
			<div style="font-size: 11px;">
                <?= $position->cost ? $position->cost_real : ' ' ?>
			</div>
			<br style="mso-data-placement:same-cell;"/>
    <?php endforeach ?>
<?php endif; ?>