<?php

use yii\web\View;

/**
 * @var View $this
 * @var string $name
 * @var string $site
 * @var string $template
 * @var int $amount
 */

?>

<?= strtr($template, [
    '{site}' => $site,
    '{name}' => $name,
    '{amount}' => $amount,
]) ?>

<hr>

<?= $this->render('_noreply') ?>
