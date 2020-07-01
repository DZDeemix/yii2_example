<?php

use yii\helpers\Html;
use yii\bootstrap\Button;
use yii\helpers\Url;
use yz\icons\Icons;
use yz\admin\widgets\Box;
use yz\admin\grid\GridView;
use yz\admin\widgets\ActionButtons;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var modules\profiles\backend\models\LeaderSearch $searchModel
 * @var array $columns
 */

$this->title = modules\profiles\common\models\Leader::modelTitlePlural();
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<?php $box = Box::begin(['cssClass' => 'leader-index box-primary']) ?>
    <div class="text-right">
        <?php echo ActionButtons::widget([
            'order' => [/*['search'],*/ ['export',  'create',  'return']],
            'gridId' => 'leader-grid',
            'searchModel' => $searchModel,
            'modelClass' => 'modules\profiles\common\models\Leader',
           /* 'buttons' => [
                'import' => function () {
                    return Button::widget([
                        'tagName' => 'a',
                        'label' => yz\icons\Icons::p('upload') . \Yii::t('admin/t', 'Импорт'),
                        'encodeLabel' => false,
                        'options' => [
                            'href' => yii\helpers\Url::to('/profiles/import/leaders/index'),
                            'class' => 'btn btn-default',
                            'id' => 'action-button-import',
                        ],
                    ]);
                },
                'delete' => function() {
                    return Button::widget([
                        'tagName' => 'a',
                        'label' => Icons::p('trash-o') . Yii::t('admin/t', 'Delete Checked'),
                        'encodeLabel' => false,
                        'options' => [
                            'href' => Url::to('/profiles/leaders/delete'),
                            'class' => 'btn btn-danger',
                            'data-grid' => 'leader-grid',
                            'data-grid-bind' => 'selection',
                            'data-grid-param' => 'id',
                            'data-method' => 'post',
                            'data-confirm' => 'Вы уверены, что хотите удалить эти элементы?'
                        ],
                    ]);
                },
            ]*/
        ]) ?>
    </div>

    <?= GridView::widget([
        'id' => 'leader-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => array_merge([
            ['class' => 'yii\grid\CheckboxColumn'],
        ], $columns, [
            [
                'class' => 'yz\admin\widgets\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ]),
    ]); ?>
<?php Box::end() ?>
