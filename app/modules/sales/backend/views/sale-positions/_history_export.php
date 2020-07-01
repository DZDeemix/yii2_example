<?php

use modules\sales\common\models\Sale;
use modules\sales\common\models\SaleHistory;

/** @var Sale $sale */
/** @var SaleHistory[] $history */
$history = $sale->getOrderedHistory();
?>

<?php if (!empty($history)): ?>
    <?php foreach ($history as $h): ?>
        <?= (new \DateTime($h->created_at))->format('d.m.Y H:i') . ' - ' . $h->note ?>
		<br style="mso-data-placement:same-cell;"/>

		<?= Sale::getStatusLabel($h->status_old) . ' → ' . Sale::getStatusLabel($h->status_new) ?>
		<br style="mso-data-placement:same-cell;"/>

        <?php if (!empty($h->comment)): ?>
            <?php if ($h->admin_id): ?>
				Модератор: <?= $h->comment ?>
            <?php else: ?>
				Участник: <?= $h->comment ?>
            <?php endif; ?>
			<br style="mso-data-placement:same-cell;"/>
        <?php endif ?>
    <?php endforeach ?>
<?php endif; ?>