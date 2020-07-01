<?php

use modules\sales\common\models\SalesAction;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
use yii\web\View;
use yii\widgets\MaskedInput;
use yz\admin\helpers\AdminHtml;
use yz\admin\widgets\FormBox;
use yz\admin\widgets\ActiveForm;

/**
 * @var View $this
 * @var SalesAction $model
 * @var ActiveForm $form
 */

$box = FormBox::begin(['cssClass' => 'sales-action-form box-primary', 'title' => '']);
$form = ActiveForm::begin();
$box->beginBody();
?>

<?= $form->field($model, 'action_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'action_from_local')->widget(MaskedInput::class, ['mask' => '99.99.9999'])
    ->widget(DatePicker::class, ['dateFormat' => 'dd.MM.yyyy'])->textInput(['class' => 'form-control'])->label('Дата начала акции') ?>

<?= $form->field($model, 'action_to_local')->widget(MaskedInput::class, ['mask' => '99.99.9999'])
    ->widget(DatePicker::class, ['dateFormat' => 'dd.MM.yyyy'])->textInput()->label('Дата окончания акции') ?>

<?= $form->field($model, 'sms_text')->textarea() ?>

<?= $form->field($model, 'email_theme')->textInput() ?>

<?= $form->field($model, 'email_text')->widget(\xvs32x\tinymce\Tinymce::class, [
    'pluginOptions' => [
        'plugins' => [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
        ],
        'toolbar1' => "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
        'toolbar2' => "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor ",
        'image_advtab' => true,
        'filemanager_title' => "Filemanager",
        'language' => ArrayHelper::getValue(explode('_', Yii::$app->language), '0', Yii::$app->language),
    ],
    'fileManagerOptions' => [
        'configPath' => [
            'upload_dir' => '/data/filemanager/source/',
            'current_path' => '../../../../../frontend/web/data/filemanager/source/',
            'thumbs_base_path' => '../../../../../frontend/web/data/filemanager/thumbs/',
            'base_url' => Yii::getAlias('@frontendWeb'), // <-- uploads/filemanager path must be saved in frontend
        ]
    ]
]) ?>

<?= $form->field($model, 'comment')->textarea() ?>

<?php
$box->endBody();
$box->actions([
    AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_STAY, $model->isNewRecord),
    AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_LEAVE, $model->isNewRecord),
    AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_CREATE, $model->isNewRecord),
]);
ActiveForm::end();
FormBox::end();
