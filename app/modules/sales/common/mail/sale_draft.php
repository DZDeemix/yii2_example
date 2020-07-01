<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var \yii\mail\BaseMessage $message instance of newly created mail message
 * @var \modules\sales\common\models\Sale $sale
 * @var string $comment
 * @var \modules\profiles\common\models\Profile $profile
 */

$message->setSubject('Покупку вернули на доработку');

$editUrl = ($_ENV['FRONTEND_WEB'] ?? null) . '/sales/sales/edit?id=' . $sale->id;
$btnStyle = '
	padding: 7px 22px;
    border: none;
    border-radius: 20px;
    color: white;
    font-size: 20px;
    transition: background-color 1s ease;
    background: linear-gradient(60deg, #4ab1b4, #179ad1, #105299, #e61738, #e73232, #ec6609);
    -webkit-box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .3);
    -moz-box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .3);
    -o-box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .3);
    box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .3);
    text-decoration: none;
';
$commentStyle = '
    background: beige;
    margin: 0;
    padding: 10px;
    font-size: 18px;
';
?>

<p>Уважаемый(ая) <?= $profile->full_name ?>, добрый день!<br/>
	Модератор отклонил продажу № <?= $sale->id ?>, который Вы загрузили в Estima.<br/>
	Причина отклонения:
</p>

<div style="<?= $commentStyle ?>">
    <?= $comment ?>
</div>

<?= $this->renderFile('@modules/sales/common/mail/_noreply.php'); ?>
