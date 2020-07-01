<?php

use modules\burnpoints\common\models\BurnPointSettings;
use yz\admin\helpers\AdminHtml;
use yz\admin\widgets\ActiveForm;
use yz\admin\widgets\FormBox;

/**
 * @var BurnPointSettings $model
 * @var ActiveForm $form
 */

$box = FormBox::begin(['cssClass' => 'burnpoints-settings-form box-primary', 'title' => '']);
$form = ActiveForm::begin(['layout' => 'default']);
$box->beginBody();
?>

  <div class="row">
    <div class="col-md-3">
      <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'warning_sms')->checkbox() ?>
            <?= $form->field($model, 'warning_email')->checkbox() ?>
            <?= $form->field($model, 'warning_push')->checkbox() ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'days_to_burn')->textInput() ?>
            <?= $form->field($model, 'days_warning')->textInput() ?>
            <?= $form->field($model, 'count_warnings')->textInput() ?>
        </div>
      </div>
    </div>

    <div class="col-md-9">
      <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'sms_warning')->textInput(['maxLength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'sms_nullify')->textInput(['maxLength' => true]) ?>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'email_warning_subject')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'email_warning_template')->tinyMce() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'email_nullify_subject')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'email_nullify_template')->tinyMce() ?>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'push_warning')->textInput(['maxLength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'push_nullify')->textInput(['maxLength' => true]) ?>
        </div>
      </div>
    </div>
  </div>

<?php $box = FormBox::begin(['cssClass' => 'box-default', 'title' => '']) ?>
  <h4>Описание параметров шаблона писем</h4>
  <table class="table">
    <tr>
      <th>{site}</th>
      <td>Ссылка на сайт</td>
    </tr>
    <tr>
      <th>{name}</th>
      <td>Фамилия и имя участника</td>
    </tr>
    <tr>
      <th>{amount}</th>
      <td>Количество сгорамых баллов участника</td>
    </tr>
    <tr>
      <th>{days}</th>
      <td>Дней до сгорания баллов при предупреждении</td>
    </tr>
  </table>

  <h4>Пример шаблона</h4>
  <p>Уважаемый(ая) {name}!</p>
  <p>Напоминаем Вам о необходимости участия в программе {site}.<br/>Иначе Ваши баллы в количестве {amount} на счете обнулятся
    через {days}.</p>
<?php
FormBox::end();

$box->endBody();
$box->actions([
    AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_STAY, $model->isNewRecord)
]);

ActiveForm::end();
FormBox::end();
