<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var \yii\mail\BaseMessage $content
 */

$settings = \ms\loyalty\api\common\models\ApiSettings::get();
$font = "font-size: 15px; color: #333; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;";
$bgColor = $settings->email_color_bg;
$frontUrl = empty($_ENV['FRONTEND_SPA'] ?? null) ? $_ENV['FRONTEND_WEB'] ?? null : $_ENV['FRONTEND_SPA'] ?? null;
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body style="background: <?= $bgColor ?>">
    <?php $this->beginBody() ?>
    <div align="center">
        <table border="0" cellspacing="0" cellpadding="0" style="background:#f7f7f7">
            <tr>
                <td>
                    <div align="center">
                    <table border="0" cellspacing="0" cellpadding="0" width="0" style="width:450.0pt" <?= $font ?>">
                        <?php if ($logoUrl = $settings->logoUrl): ?>
                            <tr>
                                <td style="padding:10px; border-bottom: 1px solid <?= $bgColor ?>; text-align:center; <?= $font ?>">
                                    <a href="<?= $frontUrl ?>" target="_blank">
                                        <img src="<?= $logoUrl ?>" style="max-height:100px;max-width:500px"/>
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td width="100%"
                                style="width:100.0%;background:#bdab98;
                                padding:16.5pt 37.5pt 16.5pt 37.5pt;border-radius:10px 10px 0 0">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table border="0" cellspacing="0" cellpadding="0" width="0"
                                       style="width:450.0pt;background:white">
                                    <tbody>
                                    <?= $content ?>
                                    <tr>
                                        <td style="padding:22.5pt 37.5pt 11.25pt 37.5pt">
                                            <p class="MsoNormal">
                                                <span style="font-size:10.5pt;
                                                font-family:&quot;Arial&quot;,sans-serif;color:#505050">
                                                    * Это письмо создано автоматически и не требует ответа *
                                                </span>
                                                <span style="font-family:&quot;Arial&quot;,sans-serif;color:#505050">
                                                    <u></u><u></u>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr style="height:15.0pt">
                            <td width="600"
                                style="width:375.0pt;
                                background:#bdab98;padding:0cm 0cm 0cm 37.5pt;height:15.0pt;border-radius:0 0 10px 10px">
    
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:15.0pt 37.5pt 15.0pt 37.5pt">
                                <p class="MsoNormal" align="center"
                                   style="text-align:center">
                                <span style="font-size:10.5pt;
                                font-family:&quot;Arial&quot;,sans-serif;color:#aaaaaa">
                                    Вопросы по работе сайта высылайте через форму «
                                    <a href="<?= $frontUrl . '/feedback/' ?>" target="_blank">
                                        <span style="color:#154284;text-decoration:none">Обратной связи</span>
                                    </a>» <u></u><u></u>
                                </span>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:0cm 37.5pt 37.5pt 37.5pt">
                                <p class="MsoNormal" align="center" style="text-align:center">
                                    <span style="font-size:10.5pt;font-family:&quot;Arial&quot;,sans-serif;
                                    color:#aaaaaa">Estima Bonus Club<br>
                                        <a href="https://estima.ru/" target="_blank">
                                            estima.ru
                                        </a><br>Сайт программы:
                                        <a href="<?= $frontUrl ?>" target="_blank">
                                            bonusclub.estima.ru
                                        </a>
                                        <u></u><u></u>
                                    </span>
                                </p>
                            </td>
                        </tr>
                    </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
