<?php

/* @var $this yii\web\View */

use modules\sales\common\models\Product;
use yii\helpers\Html;
use yz\admin\helpers\AdminHtml;
use yz\admin\widgets\ActiveForm;
use yz\admin\widgets\FormBox;


/* @var $model modules\sales\common\models\Sale */
/* @var $profile \modules\profiles\common\models\Profile */

$this->title = 'Редактировать продажу №' . $model->id;
$this->params['header'] = $this->title;
$this->params['breadcrumbs'][] = ['label' => 'Продажи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="row">
    <div class="col-md-9">
        <?php $box = FormBox::begin(['cssClass' => 'profile-form box-primary',
            'title' => 'Редактирование продажи № ' . $model->id]) ?>
        
        <?php $form = ActiveForm::begin(); $form->fieldConfig=[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2 col-md-4',
                'offset' => 'col-sm-offset-2',
                'wrapper' => 'col-sm-8 col-md-8',
                'error' => '',
                'hint' => 'col-sm-offset-2 col-sm-8',
            ]]?>
        <?php $box->beginBody() ?>
        <?php if (!empty($positions)): ?>
            <?php foreach ($positions as $k => $position): ?>
                <div class="row">
                    <div class="col-md-5">
                        <?= $form->field($position, "[$k]product_id")->select2(Product::getOptions(),
                            ['prompt' => '(не указан)',]) ?>
                    </div>
                    <div class="col-md-5">
                        <?= $form->field($position, "[$k]quantity")->textInput() ?>
                    </div>
                    <div class="col-md-2 col-sm-1">
                        <?= Html::a('Удалить', ['edit', 'id' => $model->id, 'position_id' => $position->id],
                            ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php $box->endBody() ?>
        <?php $box->actions([
            AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_STAY, $model->isNewRecord),
            Html::a('Выйти', ['index'],['class' => 'btn btn-primary']),
        ]) ?>
        <?php ActiveForm::end(); ?>
        <?php FormBox::end() ?>
    
        <?php $box = FormBox::begin(['cssClass' => 'profile-form box-primary',
            'title' => 'Добавить']) ?>
        <?php $form = ActiveForm::begin(); $form->fieldConfig=[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2 col-md-4',
                'offset' => 'col-sm-offset-2',
                'wrapper' => 'col-sm-8 col-md-8',
                'error' => '',
                'hint' => 'col-sm-offset-2 col-sm-8',
            ]]?>
        <?php $box->beginBody() ?>
        <?php if (!empty($addSalePositions)): ?>
            <?php foreach ($addSalePositions as $k => $addSalePosition): ?>
                <div class="row">
                    <div class="col-md-5">
                        <?= $form->field($addSalePosition, "[$k]product_id")->select2(Product::getOptions(),
                            ['prompt' => '(не указан)']) ?>
                    </div>
                    <div class="col-md-5">
                        <?= $form->field($addSalePosition, "[$k]quantity")->textInput() ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php $box->endBody() ?>
        <?php $box->actions([
            AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_STAY, $model->isNewRecord),
        ]) ?><?php ActiveForm::end(); ?>
        <?php FormBox::end() ?>

    </div>
    <div class="col-md-3" style="padding-left: 30px; border-left: 1px solid #ddd">
        <h3 class="center">История и комментарии</h3>
        <?= \modules\sales\frontend\widgets\SaleHistoryWidget::widget([
            'sale' => $model,
            'redirectSuccess' => '/sales/sales/edit?id=' . $model->id,
        ]) ?>
    </div>
</div>
