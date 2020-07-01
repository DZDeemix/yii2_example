<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yz\icons\Icons;

/**
 * @var \yii\web\View $this
 */
?>

<div class="text-center">
    <a href="<?= Url::to(['/sales/sales/file-catalog']) ?>" class="btn btn-info">
        <?= Icons::o('upload') ?>
        Скачать файл для загрузки продукции
    </a>
</div>
