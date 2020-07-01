<?php

use modules\sales\common\models\Sale;

/** @var Sale $sale */
$documents = $sale->documents;
?>

<?php if (!empty($documents)): ?>
    <?php foreach ($documents as $document): ?>
		<div><?= $document->url ?></div>
		<br style="mso-data-placement:same-cell;"/>
    <?php endforeach ?>
<?php endif; ?>