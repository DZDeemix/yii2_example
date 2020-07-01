<?php

use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;
use yii\helpers\Url;
use yz\icons\Icons;
use yz\admin\grid\GridView;
use yz\admin\widgets\ActionButtons;
use yz\admin\widgets\Box;
use modules\actions\common\models\Action;
use modules\profiles\common\models\Leader;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var modules\actions\backend\models\ActionSearch $searchModel
 * @var array $columns
 */

$this->title = modules\actions\common\models\Action::modelTitlePlural();
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
$css=<<<CSS
    .growth{
        border: 1px solid #00c0ef;
        padding: 3px;
    }
CSS;
$this->registerCss($css);
?>
<?php $box = Box::begin(['cssClass' => 'action-index box-primary']) ?>
<div class="text-right">
    <?php echo ActionButtons::widget([
        'order' => [['export', 'create']],
        'gridId' => 'action-grid',
        'searchModel' => $searchModel,
        'modelClass' => 'modules\actions\common\models\Action',
    ]) ?>
</div>
<?php //var_dump(\modules\profiles\common\models\Leader::getLeaderRegion());?>

<?= GridView::widget([
    'id' => 'action-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => array_merge(
        $columns, [
        [
            'class' => 'yz\admin\widgets\ActionColumn',
            'template' => '{update} {delete}',
            'buttons' => [
                'update' => function ($url, Action $model, $key) {
                    if (false === $model->statusManager->canView()) {
                        return null;
                    }

                    return Html::a(Icons::i('edit fa-lg'), $url, [
                        'title' => Yii::t('admin/t', 'Update'),
                        'class' => 'btn btn-success btn-sm',
                        'data-pjax' => '0',
                    ]);
                },
                'delete' => function ($url, Action $model, $key) {
                    if (false === $model->statusManager->canBeDeleted()) {
                        return null;
                    }

                    return Html::a(Icons::i('trash-o fa-lg'), $url, [
                        'title' => Yii::t('admin/t', 'Delete'),
                        'data-confirm' => Yii::t('admin/t', 'Are you sure to delete this item?'),
                        'class' => 'btn btn-danger btn-sm',
                        'data-pjax' => '0',
                    ]);
                }
            ]
        ],
    ]),
]); ?>

<?php Box::end() ?>