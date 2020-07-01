<?php

use modules\burnpoints\common\models\BurnPointSettings;
use yii\web\View;
use yz\admin\widgets\ActionButtons;
use yz\admin\widgets\Box;

/**
 * @var View $this
 * @var BurnPointSettings $model
 */

$this->title = \Yii::t('admin/t', 'Update {item}', ['item' => BurnPointSettings::modelTitle()]);
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>

<div class="burnpoints-settings-update">

    <div class="text-right">
        <?php Box::begin() ?>
        <?= ActionButtons::widget([
            'order' => [['index', 'return']],
            'addReturnUrl' => false,
        ]) ?>
        <?php Box::end() ?>
    </div>

    <?= $this->render('_form', compact('model')); ?>

</div>
