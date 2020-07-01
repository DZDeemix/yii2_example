<?php

use modules\sales\common\models\Sale;
use modules\sales\common\models\SaleHistory;

/** @var Sale $sale */
/** @var SaleHistory[] $history */
$history = $sale->getOrderedHistory();
?>
<div style="overflow: auto; height:150px;">
<?php if (!empty($history)): ?>
    <?php foreach ($history as $h): ?>
		<div>
            <?= (new \DateTime($h->created_at))->format('d.m.Y H:i') . ' - ' . $h->note ?>
		</div>
		<div>
			<i><?= Sale::getStatusLabel($h->status_old) . ' → ' . Sale::getStatusLabel($h->status_new) ?></i>
		</div>
        <?php if (!empty($h->comment)): ?>
			<div>
                <?php if ($h->admin_id): ?>
					Модератор: <b><?= $h->comment ?></b>
                <?php else: ?>
					Участник: <b><?= $h->comment ?></b>
                <?php endif; ?>
			</div>
        <?php endif ?>
		<br style="mso-data-placement:same-cell;"/>
    <?php endforeach ?>
<?php endif; ?>
</div>
