<?php
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var bool $allowNewSales
 */

\yz\icons\FontAwesomeAsset::register($this);
?>

<div class="col-md-12">

    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="pull-right">
                <a href="<?= Url::to(['/sales/sales/index']) ?>">Список оформленных покупок</a>
            </div>
            Совершенные покупки продукции
        </div>
        <div class="panel-body">
            <?php if ($allowNewSales): ?>
                <a class="btn btn-primary" href="<?= Url::to(['/sales/sales/app']) ?>">
                    <i class="fa fa-plus"></i>
                    Оформить новую покупку
                </a>
            <?php endif ?>

            <?php if ($allowNewSales == false): ?>
                <strong>Добавление новых покупок запрещено</strong>
            <?php endif ?>
        </div>
    </div>

</div>