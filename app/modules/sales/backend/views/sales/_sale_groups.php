<?php

use modules\sales\common\models\Sale;

/** @var Sale $sale */
$positions = $sale->positions;
?>

<?php if (!empty($positions)): ?>
    <?php foreach ($positions as $position): ?>
        <?php if ($product = $position->product): ?>
			<?php if ($group = $product->group): ?>
				<div style="font-size: 11px;">
                    <?= $group->name ?>
				</div>
				<br style="mso-data-placement:same-cell;"/>
			<?php endif; ?>
        <?php endif; ?>
    <?php endforeach ?>
<?php endif; ?>