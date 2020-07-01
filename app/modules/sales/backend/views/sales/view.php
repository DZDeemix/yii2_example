<?php

use modules\sales\backend\assets\FancyBoxAsset;
use modules\sales\common\models\Sale;
use modules\sales\common\sales\statuses\Statuses;
use marketingsolutions\thumbnails\Thumbnail;
use modules\sales\frontend\models\ApiSale;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yz\admin\tinymce\TinyMCE;
use yz\admin\widgets\ActionButtons;
use yz\admin\widgets\Box;
use yz\admin\widgets\FormBox;
use yz\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var modules\sales\common\models\Sale $model
 */
$this->title = \Yii::t('admin/t', 'Update {item}', ['item' => modules\sales\common\models\Sale::modelTitle()]);
$this->params['breadcrumbs'][] = ['label' => modules\sales\common\models\Sale::modelTitlePlural(), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;

$history = $model->getOrderedHistory();

FancyBoxAsset::register($this);
?>
	<div class="sale-update">

		<div class="text-right">
            <?php Box::begin() ?>
            <?= ActionButtons::widget([
                'order' => [['index', 'return']],
                'addReturnUrl' => false,
            ]) ?><?php Box::end() ?>
		</div>

		<div class="row">
			<div class="col-md-6">
                <?php $box = Box::begin(['cssClass' => 'sale-form box-primary', 'title' => '']) ?>

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'created_at',
                        [
                            'attribute' => 'status',
                            'value' => $model->getStatusText(),
                        ],
                        'bonuses',
                        'profile.full_name',
                        'profile.phone_mobile',
                        'profile.email',
                        'profile.city.title',
                    ],
                ]) ?>

				<div style="margin-top: 10px; text-align: center;">
                    <a href="<?= $_ENV['BACKEND_WEB'] ?? null ?>/profiles/profiles/update?id=<?= $model->recipient_id ?>"
					   target="_blank" style="text-decoration: underline">Профиль участника</a>
				</div>

				<h3>Состав продажи</h3>

				<table class="table">
					<tr>
						<th>Группа товаров</th>
						<th>Товар</th>
						<th>Количество</th>
						<th>Бонусы</th>
						<th>Стоимость</th>
					</tr>
                    <?php foreach ($model->positions as $position): ?>
						<tr>
							<td><?= $position->category ? Html::encode($position->category->name) : null ?></td>
							<td><?= Html::encode($position->product->name) ?></td>
							<td><?= $position->quantity ?></td>
							<td><?= $position->bonuses ?></td>
							<td><?= $position->cost_real ?></td>
						</tr>
                    <?php endforeach ?>

					<tr>
						<th colspan="5" class="text-center">Фото ПРОЕКТ</th>
					</tr>
					<tr>
						<th colspan="5">
                            <?= $this->render('@ms/files/attachments/common/views/partial/_files', [
                                'files' => $model->getAttachedFiles(null, Sale::class),
                                'form' => true
                            ]); ?>
						</th>
					</tr>
					<tr>
						<th colspan="5" class="text-center">Фото РЕАЛИЗАЦИЯ</th>
					</tr>
					<tr>
						<th colspan="5">
                            <?= $this->render('@ms/files/attachments/common/views/partial/_files', [
                                'files' => $model->getAttachedFiles(null, ApiSale::class),
                                'form' => true
                            ]); ?>
						</th>
					</tr>
					<tr>
						<th colspan="5" class="text-center">Итого бонусов: <?= $model->bonuses ?></th>
					</tr>
				</table>

                <?php $box = Box::end() ?>

                <?php $box = FormBox::begin(['cssClass' => 'callback-form box-primary', 'title' => 'История']) ?>
                <?php $box->beginBody() ?>
                <?php if (!empty($history)): ?><?php foreach ($history as $h): ?>
					<div>
						<div><?= (new \DateTime($h->created_at))->format('d.m.Y H:i') . ' - ' . $h->note ?></div>
						<div><i><?= Sale::getStatusLabel($h->status_old)
                                . ' → ' . Sale::getStatusLabel($h->status_new) ?></i></div>
                        <?php if (!empty($h->comment)): ?>
							<div>
                                <?= empty($h->admin_id) ? 'Участник' : 'Модератор' ?>:
								<b><?= $h->comment ?></b>
							</div>
                        <?php endif; ?>
					</div>
					<hr/>
                <?php endforeach; ?><?php endif; ?>
                <?php $box->endBody() ?>
                <?php FormBox::end() ?>
			</div>
			<div class="col-md-6">
                <?php Box::begin(['cssClass' => 'box-primary', 'title' => null]) ?>



                <?php if ($model->status == Statuses::PAID): ?>
					<div>
						<span class="label label-success">БОНУСЫ ЗАЧИСЛЕНЫ</span>
					</div>
                <?php endif; ?>

                <?php if ($model->statusManager->adminCanEdit()): ?>
					<div class="panel panel-success">
						<div class="panel-heading">
							Редактирование продажи
						</div>
						<div class="panel-body">
							<a class="btn btn-success btn-block" href="<?= Url::to(['edit', 'id' => $model->id]) ?>">
								<i class="fa fa-spin"></i> Изменить состав продажи
							</a>
						</div>
					</div>
                <?php endif ?>

                <?php if ($model->statusManager->adminCanSetStatus(Statuses::PAID)): ?>
					<div class="panel panel-success">
						<div class="panel-heading">
							Начисление бонусов
						</div>
						<div class="panel-body">
							<a class="btn btn-success btn-block"
							   href="<?= Url::to(['change-status', 'id' => $model->id, 'status' => Statuses::PAID]) ?>">
								<i class="fa fa-dollar"></i> Начислить баллы
							</a>
						</div>
					</div>
                <?php endif ?>

                <?php if ($model->statusManager->adminCanSetStatus(Statuses::APPROVED)): ?>
					<div class="panel panel-success">
						<div class="panel-heading">
							Одобрение продажи
						</div>
						<div class="panel-body">
							<a class="btn btn-success btn-block"
							   href="<?= Url::to(['change-status', 'id' => $model->id, 'status' => Statuses::APPROVED]) ?>">
								<i class="fa fa-check"></i> Одобрить продажу
							</a>
						</div>
					</div>
                <?php endif ?>

                <?php if ($model->statusManager->adminCanSetStatus(Statuses::DECLINED)): ?>
					<div class="panel panel-danger">
						<div class="panel-heading">
							Отклонение продажи
						</div>
						<div class="panel-body">
                            <?= Html::beginForm(['change-status'], 'post') ?>
                            <?= Html::hiddenInput('id', $model->id) ?>
                            <?= Html::hiddenInput('status', Statuses::DECLINED) ?>
							<div class="form-group">
								<label for="">Комментарий</label>
                                <?= TinyMce::widget([
                                    'name' => 'comment',
                                    'clientOptions' => [
                                        'language' => 'ru',
                                        'plugins' => [
                                            "advlist autolink lists link charmap print preview anchor",
                                            "searchreplace visualblocks code fullscreen",
                                            "insertdatetime media table contextmenu paste"
                                        ],
                                        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter "
                                            . "alignright alignjustify | bullist numlist outdent indent | link image"
                                    ]
                                ]); ?>
							</div>
							<button type="submit" class="btn btn-danger btn-block">
								<i class="fa fa-cross"></i> Отклонить продажу
							</button>
                            <?= Html::endForm() ?>
						</div>
					</div>
                <?php endif ?>

                <?php if ($model->statusManager->adminCanSetStatus(Statuses::DRAFT)): ?>
					<div class="panel panel-success">
						<div class="panel-heading">
							Возврат участнику
						</div>
						<div class="panel-body">
                            <?= Html::beginForm(['change-status'], 'post') ?>
                            <?= Html::hiddenInput('id', $model->id) ?>
                            <?= Html::hiddenInput('status', Statuses::DRAFT) ?>
                            <?= TinyMce::widget([
                                'name' => 'comment',
                                'clientOptions' => [
                                    'language' => 'ru',
                                    'plugins' => [
                                        "advlist autolink lists link charmap print preview anchor",
                                        "searchreplace visualblocks code fullscreen",
                                        "insertdatetime media table contextmenu paste"
                                    ],
                                    'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter "
                                        . "alignright alignjustify | bullist numlist outdent indent | link image"
                                ]
                            ]); ?>
							<button type="submit" class="btn btn-default btn-block">
								<i class="fa fa-cross"></i> Перевести в статус "Черновик" и вернуть участнику
							</button>
                            <?= Html::endForm() ?>
						</div>
					</div>
                <?php endif ?>

                <?php if ($model->status == 'paid' && ($model->statusManager->adminCanSetStatus(Statuses::ROLLBACK))): ?>
					<div class="form-group" style="margin-top: 10px;">
						<a id="back_sale" class="btn btn-default btn-block"
						   href="<?= Url::to(['rollback', 'id' => $model->id]) ?>">Откатить покупку и снять начисленные баллы</a>
					</div>
                <?php endif; ?>

                <?php Box::end() ?>
			</div>
		</div>

	</div>

<?php

# JS
$js = <<<JS
	jQuery(document).ready(function() {
		jQuery('.fancybox').fancybox({
        	autoSize    : false,
        	width       : '100%',
        	height      : '100%',
        	helpers : {
    			title: {
      				type: 'inside',
      				position: 'top'
    			}
  			},
  			beforeLoad : function() {
     			this.title = '<a class="btn btn-default" href="' + this.title + '" target="_blank">'
     				+ '<i class="fa fa-external-link-square" style="color:red"></i> ' + this.title + '</a>';
    		}
		});
		jQuery('.fancybox-pdf').fancybox({
    		type   : 'iframe',
        	autoSize    : false,
        	width       : '100%',
        	height      : '100%',
        	helpers : {
    			title: {
      				type: 'inside',
      				position: 'top'
    			}
  			},
  			beforeLoad : function() {
     			this.title = '<a class="btn btn-default" href="' + this.title + '" target="_blank">'
     				+ '<i class="fa fa-external-link-square" style="color:red"></i> ' + this.title + '</a>';
    		}
		});
	});
JS;
$this->registerJs($js);
