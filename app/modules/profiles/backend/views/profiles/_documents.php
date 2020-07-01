<?php
/** @var array $documents */
?>

<?php if (!empty($documents)): ?>
    <?php foreach ($documents as $document): ?>
        <div>
            <?php if (strpos($document, '.pdf') !== false): ?>
                <a href="<?= $document ?>" class="btn btn-default fancybox-pdf" target="_blank"
					title="<?= $document ?>">
					<i class="fa fa-pdf" style="font-size:40px; color:darkred"></i>
				</a>
            <?php else: ?>
                <a href="<?= $document ?>" class="btn btn-default fancybox" target="_blank"
				   title="<?= $document ?>">
                    <img src="<?= $document ?>" style="max-width:150px; max-height:150px;"/>
                </a>
            <?php endif; ?>
        </div>
    <?php endforeach ?>
<?php endif; ?>