<?php

use modules\profiles\common\models\Dealer;
use ms\loyalty\location\common\models\City;
use yz\admin\helpers\AdminHtml;
use yz\admin\widgets\FormBox;
use yz\admin\widgets\ActiveForm;
use modules\profiles\common\models\Companies;

/**
 * @var yii\web\View $this
 * @var modules\profiles\common\models\Dealer $model
 * @var yz\admin\widgets\ActiveForm $form
 */
?>

<?php $box = FormBox::begin(['cssClass' => 'dealer-form box-primary', 'title' => '']) ?>
<?php $form = ActiveForm::begin(); ?>

<?php $box->beginBody() ?>
<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'company_id')->dropDownList(Companies::optionValue(), ['prompt' => 'Выберите сомпанию...']) ?>
<?= $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'class')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'type')->select2(Dealer::getTypeOptions(), ['prompt' => '(не указан)']) ?>
<?= $form->field($model, 'city_id')->select2(City::getOptions(), ['prompt' => '(не указан)']) ?>
<?php $box->endBody() ?>

<?php $box->actions([
    AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_STAY, $model->isNewRecord),
    AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_LEAVE, $model->isNewRecord),
    AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_CREATE, $model->isNewRecord),
]) ?>
<?php ActiveForm::end(); ?>

<?php FormBox::end() ?>
