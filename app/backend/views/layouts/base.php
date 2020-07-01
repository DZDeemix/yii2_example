<?php
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var string $content
 */

/**
 * By default this layout simply renders layout
 * of the admin module. But if you need some customizations
 * you can overwrite this file
 */

$css = <<<CSS
	body {
		background: url(/images/bg.jpg) no-repeat top left !important;
	}
CSS;
$this->registerCss($css);

$this->beginContent('@ms/loyalty/theme/backend/views/layouts/base.php');
echo $content;
$this->endContent();
