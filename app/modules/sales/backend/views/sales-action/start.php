<?php
use yz\admin\widgets\Box;
use yz\admin\helpers\AdminHtml;
use yz\admin\widgets\ActiveForm;
/**
 * Created by PhpStorm.
 * User: Mihon
 * Date: 09.06.2018
 * Time: 20:57
 */
?>
<?php  Box::begin() ?>
    <h3>Запуск акции</h3>
 <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <br/><br/>
<div class="row">
    <div class="col-md-12">
        <h4>Отправить приглашение по SMS</h4>
        <br/><br/>
        <?= $form->field($model,'sms_text')->textarea(['rows'=>6])?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <h4>Отправить приглашение по E-MAIL</h4>
        <br/><br/>
        <?= $form->field($model,'email_text')->tinyMce([
            'rows' => 40,
            'fontsize_formats' => '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 26pt 36p',
            'toolbar' => "undo redo | styleselect | bold italic size | alignleft aligncenter alignright alignjustify"
                . "| bullist numlist outdent indent | link image | fontselect | fontsizeselect"
        ])?>
    </div>
</div>
<br/>
<br/>
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-primary" type="submit">
            Стартовать и разослать приглашения
        </button>
    </div>
</div>
<?php ActiveForm::end() ?>
<?php  Box::end() ?>