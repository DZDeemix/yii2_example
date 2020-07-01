<?php

use modules\actions\common\models\Action;
use modules\sales\common\models\Product;
use yz\admin\helpers\AdminHtml;
use yz\admin\widgets\ActiveForm;
use yz\admin\widgets\FormBox;

/**
 * @var yii\web\View $this
 * @var modules\actions\common\models\ActionParticipant $model
 * @var yz\admin\widgets\ActiveForm $form
 */
?>

<?php $box = FormBox::begin(['cssClass' => 'action-profile-form box-primary', 'title' => '']) ?>
<?php $form = ActiveForm::begin(); ?>

<?php $box->beginBody() ?>
<?= $form->field($model, 'action_id')->select2(Action::getList(), [
    'disabled' => 'disabled',
])->label('Акция')
?>



<?= $form->field($model, 'product_id')->select2(Product::getOptions(), [
    //'multiple' => 'multiple',
])->label('Товар')
?>
<?= $form->field($model, 'bonus')->textInput() ?>

<?php $box->endBody() ?>

<?php $box->actions([
    AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_STAY, $model->isNewRecord),
    AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_LEAVE, $model->isNewRecord),
    AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_CREATE, $model->isNewRecord),
]) ?>
<?php ActiveForm::end(); ?>

<?php FormBox::end() ?>
