<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var \yii\mail\BaseMessage $message instance of newly created mail message
 * @var \modules\sales\common\models\Sale $sale
 * @var \modules\profiles\common\models\Profile $profile
 */
?>

<p>Уважаемый(ая) <?= $profile->full_name ?>, добрый день!<br/>
	Мы подтвердили продажу № <?= $sale->id ?>, который Вы загрузили в Estima.<br/>
	Вам начислено <?= $sale->bonuses ?> баллов.</p>

<?= $this->renderFile('@modules/sales/common/mail/_noreply.php'); ?>

