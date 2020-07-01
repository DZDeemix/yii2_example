<?php

use modules\profiles\common\models\Profile;
use yz\admin\helpers\AdminHtml;
use yz\admin\widgets\ActiveForm;
use yz\admin\widgets\FormBox;

/**
 * @var yii\web\View $this
 * @var modules\sales\common\models\Product $model
 * @var yz\admin\widgets\ActiveForm $form
 */

\marketingsolutions\assets\AngularAsset::register($this);

?>
	<div ng-app="productForm" ng-controller="productForm">
        <?php $box = FormBox::begin(['cssClass' => 'product-form box-primary', 'title' => '']) ?>
        <?php $form = ActiveForm::begin(); ?>

        <?php $box->beginBody() ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'bonuses_formula')->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'role')->select2(Profile::getRoleOptions(), ['prompt' => '(не указан)']) ?>

		<?= $form->field($model, 'photo')->fileInput() ?>
        <?= $this->render('_image', ['model' => $model, 'field' => 'photo_name']); ?>

        <?= $form->field($model, 'enabled')->checkbox() ?>
        <?= $form->field($model, 'is_show_on_main')->checkbox() ?>

		<?= $form->field($model, 'title')->textInput() ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>
        <?= $form->field($model, 'url')->input('url') ?>
        <?= $form->field($model, 'url_shop')->input('url') ?>
        <?= $form->field($model, 'price')->input('number') ?>
        <?= $form->field($model, 'weight')->input('number') ?>
        <?= $form->field($model, 'unit_id')->select2($model->getUnitIdValues()) ?>
        <?= $form->field($model, 'name_html')->tinyMce(['rows' => '1']) ?>

		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<p>
					<a href="#" ng-click="helpVisible = !helpVisible">Помощь по формулам</a>
				</p>
			</div>
		</div>

		<div class="row" ng-show="helpVisible">
			<div class="col-sm-8 col-sm-offset-2">
				<p>
					Бонусная формула используется для вычисления значения бонусов на основе введенной пользователем
					информации о закупке. Пользователь указывает количество единиц и формула пересчитывает их в бонусы.
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
						<td>q</td>
						<td>Количество единиц товара</td>
					</tr>
				</table>
				<p>
					Примеры формул:
				</p>
				<table class="table">
					<tr>
						<td><code>20 * q</code></td>
						<td>Начисляет 20 бонусов за каждую единицу товара</td>
					</tr>
					<tr>
						<td><code>20 * 0.5 * 0.25 * q</code></td>
						<td>Начисляет {{ Math.ceil(20 * 0.5 * 0.25) }} баллов за каждую единицу</td>
					</tr>
				</table>
			</div>
		</div>
        <?php $box->endBody() ?>

        <?php $box->actions([
            AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_STAY, $model->isNewRecord),
            AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_LEAVE, $model->isNewRecord),
            AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_CREATE, $model->isNewRecord),
        ]) ?>
        <?php ActiveForm::end(); ?>

        <?php FormBox::end() ?>

	</div>

<?php

$js = <<<'JS'
var app = angular.module('productForm', []);
app.controller('productForm', ['$scope',
    function ($scope) {
        $scope.Math = window.Math;
        $scope.formula = '';
    }
])
JS;

$this->registerJs($js, \yii\web\View::POS_HEAD);
