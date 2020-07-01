<?php

use yii\helpers\Html;
use yz\admin\widgets\Box;
use yz\admin\widgets\ActionButtons;

/**
 * @var yii\web\View $this
 * @var modules\actions\common\models\Action $model
 * @var array $actionTypes
 * @var array $regions
 * @var array $cities
 * @var array $profiles
 */

$this->title = \Yii::t('admin/t', 'Create {item}', ['item' => modules\actions\common\models\Action::modelTitle()]);
$this->params['breadcrumbs'][] = ['label' => modules\actions\common\models\Action::modelTitlePlural(), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="action-create">

    <div class="text-right">
        <?php  Box::begin() ?>
        <?=  ActionButtons::widget([
            'order' => [['index', 'return']],
            'addReturnUrl' => false,
        ]) ?>
        <?php  Box::end() ?>
    </div>

    <?php echo $this->render('_form', [
        'model' => $model,
        'actionTypes' => $actionTypes,
        'regions' => $regions,
        'cities' => $cities,
        'profiles' => $profiles,
    ]); ?>

</div>
