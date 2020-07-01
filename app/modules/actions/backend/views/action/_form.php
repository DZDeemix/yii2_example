<?php

use kartik\date\DatePicker;
use modules\profiles\common\models\Profile;
use yii\web\View;
use yii\helpers\Json;
use yz\admin\helpers\AdminHtml;
use yz\admin\widgets\ActiveForm;
use yz\admin\widgets\FormBox;
use modules\profiles\common\models\Dealer;
use modules\profiles\common\managers\RoleManager;
use modules\sales\common\models\Group;
use modules\sales\common\models\Category;
use modules\sales\common\models\Product;
use modules\actions\common\models\ActionType;
use modules\actions\common\models\Action;
use modules\actions\backend\forms\ActionForm;
use modules\profiles\backend\rbac\Rbac;
use yii\helpers\FileHelper;

/**
 * @var yii\web\View $this
 * @var ActionForm $model
 * @var yz\admin\widgets\ActiveForm $form
 * @var array $actionTypes
 * @var array $regions
 * @var array $cities
 * @var array $profiles
 */

$this->registerJs("var actionTypes = " . Json::encode($actionTypes) . ";", View::POS_BEGIN);
$dir = Yii::getAlias(DIRECTORY_SEPARATOR == '\\' ? '@frontendWebroot/data/filemanager/source/' : '@data/filemanager/source/');
FileHelper::createDirectory($dir);
$thumbsDir = Yii::getAlias(DIRECTORY_SEPARATOR == '\\' ? '@frontendWebroot/data/filemanager/thumbs/' : '@data/filemanager/thumbs/');
FileHelper::createDirectory($thumbsDir);
?>

<?php $box = FormBox::begin(['cssClass' => 'action-form box-primary', 'title' => '']) ?>
<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'js-action-form'
    ]
]); ?>

<?php $box->beginBody() ?>

<?= $form->errorSummary($model) ?>

    <div class="panel panel-info">
        <div class="panel-heading">
            Описание акции
        </div>
        <div class="panel-body">

            <?php if (false === $model->isNewRecord) : ?>
                <div class="form-group">
                    <label class="control-label col-sm-2">Создал</label>
                    <div class="col-sm-8 mt-5">
                        <?= $model->admin->name ?>
                    </div>
                </div>
            <?php endif ?>

            <?= $form->field($model, 'title')->textInput() ?>

            <?= $form->field($model, 'short_description')->textarea() ?>
            <?= $form->field($model, 'description')->widget(\xvs32x\tinymce\Tinymce::class, [
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
                    'language' => 'ru',
                ],
                'fileManagerOptions' => [
                    'configPath' => [
                        'upload_dir' => '/data/filemanager/source/',
                        'current_path' => '../../../../../frontend/web/data/filemanager/source/',
                        'thumbs_base_path' => '../../../../../frontend/web/data/filemanager/thumbs/',
                        'base_url' => Yii::getAlias('@frontendWeb'),
                        // <-- uploads/filemanager path must be saved in frontend
                    ]
                ]
            ]) ?>
        </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading">
            Сроки проведения акции
        </div>
        <div class="panel-body">
            <?= $form->field($model, 'start_on_local')->datePicker() ?>
            <?= $form->field($model, 'end_on_local')->datePicker() ?>
        </div>
    </div>


    <div class="panel panel-info" id="actions">
        <div class="panel-heading">
            Механика акции
        </div>
        <div class="panel-body">

            <?= $form->field($model, 'role')->select2( Profile::getRoleOptions(), [
                'prompt' => 'Выбрать...',
            ]) ?>



            <?= $form->field($model, 'type_id')->radioList(ActionType::getListActive(), [
                'class' => 'js-action-type',
            ]) ?>
            <!--  <?= $form->field($model, 'adminIds')->select2(Rbac::getRolesList(), [
             'multiple' => 'multiple',
         ])->label('Продажа требует подтверждения') ?>
            <?= $form->field($model, 'bonuses_amount', [
                'options' => [
                    'class' => 'form-group js-action-bonuses-amount hidden'
                ]
            ])->textInput() ?>

            <?= $form->field($model, 'personal_plan_formula', [
                'options' => [
                    'class' => 'form-group js-personal_plan_formula hidden'
                ]
            ])->textInput() ?>

            <?= $form->field($model, 'confirm_period', [
                'options' => [
                    'class' => 'form-group js-confirm_period'
                ]
            ])->textInput() ?>

            <?= $form->field($model, 'plan_amount', [
                'options' => [
                    'class' => 'form-group js-action-plan-amount hidden'
                ]
            ])->textInput() ?>
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 js-action-period-info-box ">
                    <p>
                        После истечения периода,
                        если участие не подтверждено пользователем,
                        внесение продаж по акции будет недоступно.
                        Если поле не заполнено, то продажи можно вносить в течение всего периода действия акции
                    </p>
                </div>
            </div>
            <?= $form->field($model, 'bonuses_formula')->textInput() ?>
-->

            <!--
            ->hint('<a class="js-action-bonuses-info-show" href="#">Помощь по формулам</a>')
            div class="row">
                <div class="col-sm-8 col-sm-offset-2 js-action-bonuses-info-box hidden">
                    <p>
                        <b>Бонусная формула</b> используется для вычисления значения бонусов на основе введенной пользователем
                        информации о продаже. Пользователь указывает стоимость продажи и формула пересчитывает ее в бонусы.
                        Результат будет округлен до ближайшего целого.
                    </p>
                    <p>
                        <b>Формула расчета индивидуального плана</b> используется для вычисления индивидуального плана путем умножения
                        загруженного из xls-файла прошлогоднего плана на коэфициент.
                        Если формула для рассчета индивидуального плана не указана, то план равняется прошлогоднему план-факту,
                        загруженному из xls-файла и загруженной из xls-файла шкалы зависимости бонусов в зависимости от прироскта.
                        Результат будет округлен до ближайшего целого.
                    </p>

                    <p>
                        В формуле Вы можете использовать переменные:
                    </p>
                    <table class="table">
                        <tr>
                            <th>Имя</th>
                            <th>Значение</th>
                        </tr>
                        <tr>
                            <td>bonuses</td>
                            <td>Сумма продажи</td>
                        </tr>
                        <tr>
                            <td>plan</td>
                            <td>план-факт прошлого года</td>
                        </tr>
                    </table>
                    <p>
                        <b>Примеры формул для бонусов:</b>
                    </p>
                    <table class="table" style="background-color: #f7fac8;">
                        <tr>
                            <td><code>200</code></td>
                            <td>Начисляет фиксировое количество баллов (200) за продажу</td>
                        </tr>
                        <tr>
                            <td><code>bonuses * 0.1</code></td>
                            <td>Начисляет 10% от суммы продажи</td>
                        </tr>
                    </table>
                    <p>
                        <b>Примеры формул для расчета плана:</b>
                    </p>
                    <table class="table" style="background-color: #f7fac8;">
                        <tr>
                            <td><code>1000</code></td>
                            <td>Устанавливает фиксированный план (1000) у всех участников акции</td>
                        </tr>
                        <tr>
                            <td><code>plan * 2</code></td>
                            <td>Устанавливает план текущей акции в 2 раза выше прошлогоднего план-факта</td>
                        </tr>
                    </table>

                </div>
            </div-->

           <!-- <?= $form->field($model, 'pay_type')->select2(Action::getPayTypesList(), [
                'prompt' => 'Выбрать...',
                'class' => 'form-control js-action-pay-type',
            ]) ?>

            <?= $form->field($model, 'pay_threshold', [
                'options' => [
                    'class' => 'form-group js-action-pay-threshold hidden'
                ]
            ])->textInput() ?>-->


        </div>
    </div>

    <!--div class="panel panel-info" id="actions">
        <div class="panel-heading">
            Настройки товаров
        </div>
        <div class="panel-body">
            <?= $form->field($model, 'has_products', [
                'options' => [
                    'class' => 'form-group js-action-has-products'
                ]
            ])->checkbox()->label() ?>


            <div class="js-action-products-box">
                <?= $form->field($model, 'productIds')->select2(Product::getOptions(), [
                    'multiple' => 'multiple',
                ])->label('Участвующие в акции товары')
                    ->hint('Eсли значение не выбрано, то участвуют все товары') ?>
            </div>

            <?= $form->field($model, 'show_price', [
                'options' => [
                    'class' => 'form-group'
                ]
            ])->checkbox()->label() ?>
        </div>
    </div-->

    <!--div class="panel panel-info" id="actions">
        <div class="panel-heading">
            Настройки участников
        </div>
        <div class="panel-body">
            <?= $form->field($model, 'regionIds')->select2($regions, [
        'multiple' => 'multiple',
    ])->label('Участвующие в акции регионы')
        ->hint('Eсли значение не выбрано, то участвуют все регионы') ?>


            <?= $form->field($model, 'profileIds')->select2($profiles, [
        'multiple' => 'multiple',
    ])->label('Участвующие в акции участники')
        ->hint('Eсли значение не выбрано, то участвуют все участники') ?>
        </div>
    </div-->
<?php if ($model->statusManager->canEditAction()) : ?>
<?php if (Yii::$app->controller->action->id == 'update'): ?>
    <?= $form->field($model, 'email_is_send')->staticControl(['value' => $model->email_is_send ? 'Да' : 'Нет']) ?>
    <?php if (!$model->email_is_send): ?>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <a class="btn btn-primary" href="/actions/action/email-send?action_id=<?= $model->id ?>"><i
                            class="glyphicon glyphicon-envelope"></i>&nbsp; Отправить приглашение по Email</a>
            </div>
        </div>
    <?php endif; ?>
    <br/><br/>
<?php endif; ?>
<?php endif ?>

<?php $box->endBody() ?>

<?php if ($model->statusManager->canEditAction()) : ?>

    <?php $box->actions([
        AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_STAY, $model->isNewRecord),
        AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_LEAVE, $model->isNewRecord),
    ]) ?>

<?php else: ?>

    <div class="panel panel-info">
        <div class="panel-heading">
            Изменение параметров акции невозможно
        </div>
        <div class="panel-body">
            Изменение параметров акции невозможно, так как акция завершена или в акцию уже добавлены продажи.
        </div>
    </div>

<?php endif ?>

<?php ActiveForm::end(); ?>

<?php FormBox::end() ?>