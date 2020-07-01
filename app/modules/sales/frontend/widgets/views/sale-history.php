<?php

use modules\sales\common\models\Sale;
use yii\bootstrap\ActiveForm;

/**
 * @var \yii\web\View $this
 * @var Sale $sale
 * @var \modules\sales\common\models\SaleHistory $model
 */

$history = $sale->getOrderedHistory();
?>

<?php if (!empty($history)): ?>
    <?php $form = ActiveForm::begin(['id' => 'edit-form']); ?>

		<div class="center">
            <?= $form->field($model, 'comment')->textarea(['class' => 'form-control full'])->label(false) ?>
		</div>

	<div class="form-row row-add-comment">
        <?= \yii\bootstrap\Html::submitButton('Добавить комментарий', ['class' => 'btn btn-rainbow']) ?>
	</div>
    <?php ActiveForm::end() ?>


    <?php foreach ($history as $h): ?>
		<div class="sale-history">
			<div><?= (new \DateTime($h->created_at))->format('d.m.Y H:i') . ' - ' . $h->note ?></div>
			<div><i><?= Sale::getStatusLabel($h->status_old)
                    . ' → ' . Sale::getStatusLabel($h->status_new) ?></i></div>
            <?php if (!empty($h->comment)): ?>
				<div>
					<?= empty($h->admin_id) ? 'Участник' : 'Модератор' ?>:
					<b><?= $h->comment ?></b>
				</div>
            <?php endif; ?>
		</div>
    <?php endforeach; ?>
<?php endif; ?>

