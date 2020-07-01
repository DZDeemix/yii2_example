<?php

use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var string $content
 */

?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title>Промо</title>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0"/>
	<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>
	<meta name="robots" content="noindex, nofollow"/>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
