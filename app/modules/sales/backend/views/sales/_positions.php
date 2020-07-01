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
					<?= $product->weight ?>
                    <?php if ($product->role === 'designer'): ?>
                        %
                    <?php else: ?>
                        x
                    <?php endif; ?>
                <?php endif; ?>

				<?php if ($position->cost): ?>
					, стоимость = <?= $position->cost_real ?>
				<?php endif; ?>

				<span style="margin-left: 30px; color: gray; float: right;">
                <?php if ($position->quantity || $product->unit): ?>
					<?= $position->quantity ?>
                    <?= $product->unit ? $product->unit->short_name : '' ?>=
                    <?= $position->bonuses ?> р.
                <?php endif; ?>
				</span>
			</div>
			<?php endif; ?>
        <?php endforeach ?>
<?php endif; ?>
