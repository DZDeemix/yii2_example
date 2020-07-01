<?php

/* @var $this yii\web\View */
/* @var int | null $id */

if ($id) {
    $this->title = 'Редактирование продажи';
} else {
    $this->title = 'Оформление покупки';
}
$this->params['header'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">

        <?= \modules\sales\common\app\widgets\SaleApp::widget([
            'id' => $id,
            'config' => [
                'apiUrlPrefix' => '/sales/sale-app'
            ]
        ]) ?>

    </div>
</div>
