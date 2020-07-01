<?php

/**
 * @var yii\web\View $this
 * @var modules\sales\common\models\Sale $model
 */
use yz\admin\widgets\ActionButtons;
use yz\admin\widgets\Box;

$this->title = 'Редактирование покупку';
$this->params['breadcrumbs'][] = ['label' => modules\sales\common\models\Sale::modelTitlePlural(), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="sale-app">

    <div class="text-right">
        <?php Box::begin() ?>
        <?= ActionButtons::widget([
            'order' => [['index', 'return']],
            'addReturnUrl' => false,
        ]) ?>
        <?php Box::end() ?>
    </div>

    <div class="row">
        <div class="col-lg-8">

            <?php $box = Box::begin(['title' => 'Редактирование покупки']) ?>

            <?= \modules\sales\common\app\widgets\SaleApp::widget([
                'id' => $model->id,
                'config' => [
                    'apiUrlPrefix' => '/sales/sale-app'
                ]
            ]) ?>

            <?php Box::end() ?>
        </div>
    </div>

</div>