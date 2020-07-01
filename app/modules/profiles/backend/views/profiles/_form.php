<?php

use modules\profiles\common\models\Dealer;
use modules\profiles\common\models\Profile;
use yii\helpers\FileHelper;
use yz\admin\helpers\AdminHtml;
use yz\admin\widgets\ActiveForm;
use yz\admin\widgets\Box;
use yz\admin\widgets\FormBox;
use yii\helpers\Url;
use modules\profiles\common\models\Companies;

/**
 * @var yii\web\View $this
 * @var modules\profiles\common\models\Profile $model
 * @var yz\admin\widgets\ActiveForm $form
 */

\marketingsolutions\assets\AngularAsset::register($this);

$dir = Yii::getAlias(($_ENV['YII_ENV'] ?? null) == 'dev' ? '@frontendWebroot/data/filemanager/source/' : '@data/filemanager/source/');
FileHelper::createDirectory($dir);
$thumbsDir = $dir = Yii::getAlias(($_ENV['YII_ENV'] ?? null) == 'dev' ? '@frontendWebroot/data/filemanager/thumbs/' : '@data/filemanager/thumbs/');
FileHelper::createDirectory($thumbsDir);
?>

<div class="row">
	<div class="col-md-6">
        <?php $box = FormBox::begin(['cssClass' => 'profile-form box-primary', 'title' => '']) ?><?php $form = ActiveForm::begin(); ?>

        <?php $box->beginBody() ?>

        <?= $form->field($model, 'first_name')->textInput(['maxlength' => 32]) ?>
        <?= $form->field($model, 'last_name')->textInput(['maxlength' => 32]) ?>
        <?= $form->field($model, 'middle_name')->textInput(['maxlength' => 32]) ?>
        <?= $form->field($model, 'phone_mobile_local')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '+7 999 999-99-99',
        ]) ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => 64]) ?>
        <?= $form->field($model, 'city_id')->select2(\ms\loyalty\location\common\models\City::getOptions(), ['prompt' => '(не указан)']) ?>
        <?= $form->field($model, 'dealer_id')->dropDownList(Dealer::getOptions(), ['prompt' => '(не указан)']) ?>
        <?= $form->field($model, 'role')->dropDownList(Profile::getRoleOptions(), ['prompt' => '(не указан)']) ?>

        <?php $box->endBody() ?>

        <?php $box->actions([
            AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_STAY, $model->isNewRecord),
            AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_LEAVE, $model->isNewRecord),
            AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_CREATE, $model->isNewRecord),
        ]) ?><?php ActiveForm::end(); ?>

        <?php FormBox::end() ?>

        <?php if ($model->isNewRecord == false): ?>
			<!--ДОП ИНФО УЧАСТНИКА-->
            <?php Box::begin(['title' => null]) ?>
            <?= \yii\widgets\DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'created_at:datetime',
                    'registered_at:datetime',
                    'blocked_at:datetime',
                    'banned_at:datetime',
                ],
            ]) ?>

            <?php Box::end() ?>

			<!--НДФЛ АНКЕТА УЧАСТНИКА-->
            <?php Box::begin(['title' => 'Анкета НДФЛ']) ?>
            <?= \modules\profiles\common\widgets\ProfileTaxWidget::widget(['profile' => $model]) ?>

            <?php Box::end() ?>

        <?php endif; ?>
	</div>
	<div class="col-md-6">
		<!--УЧАСТНИК-->
        <?php if ($model->isNewRecord == false): ?>

			<!--БЛОКИРОВКА УЧАСТНИКА-->
            <?php Box::begin(['title' => null]) ?>
			<div style="margin-top:-5px">
                <?php if (!$model->blocked_at): ?><span class="label label-success">активен</span><?php endif; ?>
                <?php if ($model->blocked_at): ?><span class="label label-danger">заблокирован</span><?php endif; ?>
				<b>Блокировка участника</b>
			</div>
			<hr/>
            <?php if ($model->blocked_at): ?>
				<div class="row">
					<div class="col-md-8">
						<p><?= $model->blocked_reason ?></p>
					</div>
					<div class="col-md-4">
						<a href="<?= Url::to(['/profiles/profiles/unblock', 'id' => $model->id]) ?>" type="submit"
						   class="btn btn-success btn-block pull-right">
							Разблокировать участника
						</a>
					</div>
				</div>

            <?php else: ?>

                <?php $form1 = ActiveForm::begin(['action' => ['block', 'id' => $model->id]]); ?>
				<div class="row">
					<div class="col-md-8">
                        <?= \yii\bootstrap\Html::textarea('reason', '', [
                            'class' => 'form-control',
                            'placeholder' => 'Причина блокировки'
                        ]) ?>
					</div>
					<div class="col-md-4">
						<button type="submit" class="btn btn-danger btn-block pull-right">
							Заблокировать
						</button>
					</div>
				</div>
                <?php ActiveForm::end(); ?>

            <?php endif ?>

            <?php Box::end() ?>

			<!--БАН УЧАСТНИКА-->
            <?php Box::begin(['title' => null]) ?>
			<div style="margin-top:-5px">
                <?php if (!$model->banned_at): ?><span class="label label-success">активен</span><?php endif; ?>
                <?php if ($model->banned_at): ?><span class="label label-danger">забанен</span><?php endif; ?>
				<b>Бан участника</b>
			</div>
			<hr/>
            <?php if ($model->banned_at): ?>
				<div class="row">
					<div class="col-md-8">
						<p><?= $model->banned_reason ?></p>
					</div>
					<div class="col-md-4">
						<a href="<?= Url::to(['/profiles/profiles/unban', 'id' => $model->id]) ?>" type="submit"
						   class="btn btn-success btn-block">
							Разбанить участника
						</a>
					</div>
				</div>

            <?php else: ?>

                <?php $form2 = ActiveForm::begin(['action' => ['ban', 'id' => $model->id]]); ?>
				<div class="row">
					<div class="col-md-8">
                        <?= \yii\bootstrap\Html::textarea('reason', '', [
                            'class' => 'form-control',
                            'placeholder' => 'Причина бана'
                        ]) ?>
					</div>
					<div class="col-md-4">
						<button type="submit" class="btn btn-danger btn-block pull-right">
							Забанить
						</button>
					</div>
				</div>
                <?php ActiveForm::end(); ?>

            <?php endif ?>

            <?php Box::end() ?>

			<!--ТРАНЗАКЦИИ УЧАСТНИКА-->

            <?php Box::begin(['title' => 'Баланс участника']) ?>
            <?= \modules\profiles\common\widgets\ProfilePurseWidget::widget(['profile' => $model]) ?>

            <?php Box::end() ?>

			<!--ЗАКАЗЫ УЧАСТНИКА-->
            <?php Box::begin(['title' => 'Заказы участника']) ?>
            <?= \modules\profiles\common\widgets\ProfileOrdersWidget::widget(['profile' => $model]) ?>

            <?php Box::end() ?>

        <?php endif ?>

        <?= $this->render('partials/profile-sidebar', compact('model')) ?>
	</div>
</div>
