<?php

use modules\profiles\common\models\Profile;
use yii\bootstrap\Html;
use yz\admin\grid\GridView;
use yz\admin\widgets\ActionButtons;
use yz\admin\widgets\Box;
use yz\icons\Icons;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var modules\profiles\backend\models\ProfileSearch $searchModel
 * @var array $columns
 */

$this->title = modules\profiles\common\models\Profile::modelTitlePlural();
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;

# CSS
$css = <<<CSS
	.purse-balance {
		  background: gold;
    	padding: 3px 5px;
    	border-radius: 50%;
    	border: 1px solid orange;
    	box-shadow: 0 0 5px rgba(0,0,0,0.5);
	}
CSS;
$this->registerCss($css);
?>

<?php $box = Box::begin(['cssClass' => 'profile-index box-primary']) ?>
<div class="text-right">
    <?php echo ActionButtons::widget([
        'order' => [['export', 'create', 'delete', 'return']],
        'gridId' => 'profile-grid',
        'searchModel' => $searchModel,
        'modelClass' => \modules\profiles\common\models\Profile::class,
    ]) ?>
</div>

<?= GridView::widget([
    'id' => 'profile-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => array_merge([
        ['class' => 'yii\grid\CheckboxColumn'],
    ], [
        [
            'class' => 'yz\admin\widgets\ActionColumn',
            'template' => '{chat} {login} {update} {delete}',
            'buttons' => [
                'bonus' => function ($url, Profile $model) {
                    return Html::a(Icons::i('dollar'), \yii\helpers\Url::to(['/bonuses/bonuses/create/', 'profile_id' => $model->id]), [
                        'title' => 'Добавить бонус',
                        'data-method' => 'post',
                        'class' => 'btn btn-info btn-sm',
                        'data-pjax' => '0',
                    ]);
                },
                'login' => function ($url, Profile $model) {
                    return Html::a(Icons::i('key'), '/profiles/profiles/login?id=' . $model->id, [
                        'target' => '_blank',
                        'title' => 'Войти под участником',
                        'data-method' => 'post',
                        'class' => 'btn btn-warning btn-sm',
                        'data-pjax' => '0',
                    ]);
                },
                'chat' => function ($url, Profile $model) {
                    return Html::a(Icons::i('comment'), '/tickets/profiles/chat?id=' . $model->id, [
                        'title' => 'Чат с участником',
                        'data-method' => 'post',
                        'class' => 'btn btn-info btn-sm',
                        'data-pjax' => '0',
                    ]);
                },
            ],
        ],
    ], $columns),
]); ?>
<?php Box::end() ?>


