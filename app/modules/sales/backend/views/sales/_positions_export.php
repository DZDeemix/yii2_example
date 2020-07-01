<?php

use modules\sales\common\models\Sale;

/** @var Sale $sale */
$positions = $sale->positions;
?>

<?php if (!empty($positions)): ?>
    <?php foreach ($positions as $position): ?>
        <?php if ($product = $position->product): ?>
			<div><b><?= $position->product->name ?></b></div>
			<div>
                <?php if ($group = $product->group): ?>
					<span style="text-transform:uppercase"><?= $group->name ?>.</span>
                <?php endif; ?>

                <?= $product->bonuses_formula ?>
                <?php if ($product->weight || $product->unit): ?>
					, <?= $product->weight ?>
                    <?= $product->unit ? $product->unit->short_name : '' ?>
                <?php endif; ?>

                <?php if ($position->cost): ?>
					, стоимость = <?= $position->cost_real ?>
                <?php endif; ?>

				<span style="margin-left: 30px; color: gray; float: right;">
					<?= $position->quantity ?>x = <?= $position->bonuses ?> р.
				</span>
			</div>

			<br style="mso-data-placement:same-cell;"/>
        <?php endif; ?>
    <?php endforeach ?>
<?php endif; ?>