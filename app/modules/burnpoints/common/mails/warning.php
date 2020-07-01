<?php

use modules\burnpoints\common\models\BurnPointSettings;
use yii\web\View;

/**
 * @var View $this
 * @var string $name
 * @var BurnPointSettings $settings
 * @var int $amount
 * @var int $daysBefore
 * @var string $template,
 * @var string $site
 */

$words = ['день', 'дня', 'дней'];
$num = $daysBefore % 100;
if ($num > 19) {
    $num = $num % 10;
}
$dayWord = $words[2];
switch ($num) {
    case 1:
        $dayWord = $words[0];
        break;
    case 2: case 3: case 4:
        $dayWord = $words[1];
        break;
}
?>

<?= strtr($template, [
    '{site}' => $site,
    '{name}' => $name,
    '{days}' => "{$daysBefore} {$dayWord}",
    '{amount}' => $amount,
]) ?>

<hr>

<?= $this->render('_noreply') ?>
