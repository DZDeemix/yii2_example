<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var \modules\profiles\common\models\Profile $bannedProfile
 */
?>

<div class="alert alert-info">
	Уважаемый участник! Ваша учетная запись была забанена. Вы не можете пользоваться личным кабинетом.
	<?php if (!empty($bannedProfile->banned_reason)): ?>
		Причина: <b><?= $bannedProfile->banned_reason ?></b>
	<?php endif; ?>
</div>