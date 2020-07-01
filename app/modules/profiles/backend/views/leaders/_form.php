<?php

use modules\projects\common\models\Project;
use ms\loyalty\location\common\models\District;
use yii\helpers\Html;
use yii\widgets\MaskedInput;
use yz\admin\helpers\AdminHtml;
use yz\admin\widgets\FormBox;
use yz\admin\widgets\ActiveForm;
use modules\profiles\common\models\Leader;
use modules\profiles\common\models\Region;
use modules\profiles\common\models\Profile;
use modules\profiles\backend\rbac\Rbac;

/**
 * @var yii\web\View $this
 * @var modules\profiles\common\models\Leader $model
 * @var yz\admin\widgets\ActiveForm $form
 */

?>

<?php  $box = FormBox::begin(['cssClass' => 'leader-form box-primary', 'title' => '']) ?>

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'js-leader-form'
        ]
    ]); ?>

    <?php $box->beginBody() ?>

        <?= $form->errorSummary($model) ?>

        <div class="row">

            <div class="col-md-8">

                <?= $form->field($model, 'role')->dropDownList(Rbac::getRolesList(), [
                    'prompt' => 'Выбрать роль...',
                    'class' => 'form-control js-leader-role-select',
                ]) ?>
                <?= $form->field($model, 'legal_person_id')->dropDownList(Project::getTitleOptions(), [
                    'prompt' => 'Выбрать юрлицо...',
                    'class' => 'form-control js-leader-role-select',
                ]) ?>

                <?= $form->field($model, 'first_name')->textInput() ?>

                <?= $form->field($model, 'last_name')->textInput() ?>

                <?= $form->field($model, 'middle_name')->textInput() ?>

                <?= $form->field($model, 'phone_mobile_local')->widget(MaskedInput::class, [
                    'mask' => '+7 (999) 999-99-99',
                ]) ?>

                <?= $form->field($model, 'email')->input('email') ?>

                <?= $form->field($model, 'login') ?>

                <?= $form->field($model, 'password')->input('password') ?>
                <?= $form->field($model, 'passwordCompare')->input('password') ?>


                <?= Html::hiddenInput('isNewRecord', $model->isNewRecord, ['class' => 'js-is-new-record']) ?>

            </div>

        </div>

        </div>

    <?php $box->endBody() ?>

    <?php $box->actions([
        AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_STAY, $model->isNewRecord),
        AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_LEAVE, $model->isNewRecord),
        AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_CREATE, $model->isNewRecord),
    ]) ?>
    <?php ActiveForm::end(); ?>

<?php  FormBox::end() ?>
