<?php

use yii\helpers\Html;
use yz\admin\widgets\Box;
use yz\admin\widgets\ActionButtons;

/**
 * @var yii\web\View $this
 * @var modules\actions\common\models\ActionGrowthBonus $model
 */
$this->title = \Yii::t('admin/t', 'Create {item}', ['item' => modules\actions\common\models\ActionGrowthBonus::modelTitle()]);
$this->params['breadcrumbs'][] = ['label' => modules\actions\common\models\ActionGrowthBonus::modelTitlePlural(), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="action-growth-bonus-create">

    <div class="text-right">
        <?php  Box::begin() ?>
        <?=  ActionButtons::widget([
            'order' => [['index', 'create', 'return']],
            'addReturnUrl' => false,
        ]) ?>
        <?php  Box::end() ?>
    </div>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>

</div>
