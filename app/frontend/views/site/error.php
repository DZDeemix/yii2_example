<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception \yii\base\Exception */

$this->title = $name;
?>
<div class="site-error">
    <?php if ($exception->statusCode == '404'): ?>
		<?php $this->title = '404 - Страница не найдена' ?>
		<h1 class="center">404 - Страница не найдена</h1>
    <?php else: ?>
        <?php $this->title = $exception->statusCode . '500 - Ошибка сервера'; ?>
		<h1 class="center"><?= Html::encode($this->title) ?></h1>

		<div class="alert alert-danger">
			<div><b><?= $name ?></b></div>
            <?= nl2br(Html::encode($message)) ?>
		</div>
    <?php endif; ?>
</div>