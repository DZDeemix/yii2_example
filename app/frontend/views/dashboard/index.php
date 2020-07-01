<?php

/** @var \modules\main\common\models\Profile $profile */
$profile = Yii::$app->user->identity;
?>

<?= \modules\main\frontend\widgets\LeaderWidget::widget(['profile' => $profile]) ?>

<?php if ($profile->blocked): ?>
    <?= $profile->renderBlocked() ?>
<?php endif; ?>

<?= \ms\loyalty\pages\frontend\widgets\PageWidget::widget(['url' => 'lk']) ?>


