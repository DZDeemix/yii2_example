<?php

use yii\helpers\Html;
use yz\admin\helpers\AdminHtml;
use yz\admin\widgets\Box;
use yz\admin\widgets\FormBox;
use yz\admin\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var modules\profiles\common\models\Companies $model
 * @var yz\admin\widgets\ActiveForm $form
 */
?>

<?php  $box = FormBox::begin(['cssClass' => 'companies-form box-primary', 'title' => '']) ?>
    <?php $form = ActiveForm::begin(); ?>

    <?php $box->beginBody() ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?php if(Yii::$app->controller->action->id == 'create'):?>
        <?= $form->field($model, 'created_at')->hiddenInput(['value' => date("Y-m-d H:i:s")])->label(false) ?>
    <?php else: ?>
        <?= $form->field($model, 'updated_at')->hiddenInput(['value' => date("Y-m-d H:i:s")])->label(false) ?>
    <?php endif;?>

    <?php $box->endBody() ?>

    <?php $box->actions([
        AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_STAY, $model->isNewRecord),
        AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_LEAVE, $model->isNewRecord),
        AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_CREATE, $model->isNewRecord),
    ]) ?>
    <?php ActiveForm::end(); ?>

<?php  FormBox::end() ?>
