<?php

use yii\helpers\Html;
use yz\admin\widgets\Box;
use yz\admin\grid\GridView;
use yz\admin\widgets\ActionButtons;
use modules\actions\common\models\Action;
use yz\icons\Icons;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var modules\actions\backend\models\ActionProfileSearch $searchModel
 * @var array $columns
 */

$this->title = modules\actions\common\models\ActionParticipantWithData::modelTitlePlural();
(isset(\Yii::$app->request->get('ActionParticipantSearch')["action__id"])) ? $this->title = modules\actions\common\models\ActionParticipantWithData::modelTitlePlural()." &laquo;".Action::getActionName(\Yii::$app->request->get('ActionParticipantSearch')["action__id"])."&raquo;" : $this->title = modules\actions\common\models\ActionParticipant::modelTitlePlural();
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<?php $box = Box::begin(['cssClass' => 'action-profile-index box-primary']) ?>
    <div class="text-right">
        <?php echo ActionButtons::widget([
            'order' => [/*['search'],*/ ['goback','export', 'create', 'delete']],
            'gridId' => 'action-profile-grid',
            'searchModel' => $searchModel,
            'modelClass' => 'modules\actions\common\models\ActionProfile',
            'buttons' => [
                'goback' => function () {
                    return Html::a(Icons::p('reply') . ' &nbsp;&nbsp;Назад к списку акций', ['/actions/action/index'], [
                        'class' => 'btn btn-default',
                        'title' => 'Быстрый экспорт всех',
                    ]);
                },
            ]
        ]) ?>
    </div>

    <?= GridView::widget([
        'id' => 'action-profile-grid',
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
