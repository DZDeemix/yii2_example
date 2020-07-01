<?php

use modules\sales\common\models\Sale;
use modules\sales\common\models\SalePosition;
use marketingsolutions\thumbnails\Thumbnail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model modules\sales\common\models\Sale */

$this->title = 'Информация о покупке';
$this->params['header'] = 'Информация о покупке';
$this->params['breadcrumbs'][] = ['label' => 'Покупки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?php if ($model->statusManager->recipientCanEdit()): ?>
        <a class="btn btn-success" href="<?= Url::to(['sales/app', 'id' => $model->id]) ?>"><i
                class="fa fa-pencil-o"></i> Изменить</a>
    <?php endif ?>
    <?php if ($model->statusManager->canBeDeleted()): ?>
<!--        <a class="btn btn-success" href="--><?//= Url::to(['delete']) ?><!--"><i class="fa fa-trash"></i> Удалить</a>-->
    <?php endif ?>
</p>
<div class="row">
    <div class="col-md-12">

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'status',
                    'value' => Sale::getStatusValues()[$model->status],
                ],
                [
                    'attribute' => 'review_comment',
                    'label' => 'Комментарий администратора',
                    'visible' => $model->review_comment !== null,
                ],
                'bonuses',
                'created_at:datetime',
                'sold_on:date',
            ],
        ]) ?>

        <h3>Состав покупки</h3>

        <table class="table">
            <tr>
                <th>Группа товаров</th>
                <th>Товар</th>
                <th>Количество</th>
                <th>Бонусы</th>
            </tr>
            <?php foreach ($model->positions as $position): ?>
                <tr>
                    <td><?= Html::encode($position->category->name) ?></td>
                    <td><?= Html::encode($position->product->name) ?></td>
                    <td><?= $position->quantityLocal ?> <?= $position->unit->short_name ?></td>
                    <td><?= $position->bonuses ?></td>
                </tr>
            <?php endforeach ?>
            <?php
            // if ($position->validation_method == SalePosition::VALIDATION_METHOD_SERIAL):;
            ?>
            <tr>
                <th colspan="4" class="text-center">Подтверждающие документы</th>
            </tr>

            <tr>
                <th colspan="4">
                    <div class="container-fluid">
                        <div class="row">
                            <?php foreach ($model->documents as $document): ?>
                                <div class="col-md-3">
                                    <a href="<?= Url::to(['download-document', 'id' => $document->id]) ?>" class="thumbnail">
                                        <?php if ($document->isImage): ?>
                                            <img
                                                src="<?= (new Thumbnail())->url($document->getFileName(), Thumbnail::thumb(300, 600), '300x600') ?>"
                                                alt=""/>
                                        <?php else: ?>
                                            <?= Html::encode($document->original_name) ?>
                                        <?php endif ?>
                                    </a>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </th>
            </tr>
         <?php
         // endif;
         ?>
            <tr>
                <th colspan="4" class="text-center">Итого бонусов: <?= $model->bonuses ?></th>
            </tr>
        </table>
    </div>
</div>

<div class="sale-view">


</div>
