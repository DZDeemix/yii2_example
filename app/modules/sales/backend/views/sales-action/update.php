<?php

use yii\helpers\Html;
use yii\web\View;
use yz\admin\widgets\Box;
use yz\admin\widgets\ActionButtons;
use modules\sales\common\models\SalesAction;
use modules\bonuses\common\models\Bonus;
use yii\helpers\FileHelper;

/**
 * @var View $this
 * @var SalesAction $model
 */
$this->title = 'Обновить правила акций';
$this->params['breadcrumbs'][] = ['label' => SalesAction::modelTitlePlural(), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;

$dir = Yii::getAlias(getenv('YII_ENV') == 'dev' ? '@frontendWebroot/data/filemanager/source/' : '@data/filemanager/source/');
FileHelper::createDirectory($dir);
$thumbsDir = $dir = Yii::getAlias(getenv('YII_ENV') == 'dev' ? '@frontendWebroot/data/filemanager/thumbs/' : '@data/filemanager/thumbs/');
FileHelper::createDirectory($thumbsDir);

$statusLabel = 'default';
if ($model->status === SalesAction::STATUS_CURRENT) {
    $statusLabel = 'success';
} elseif ($model->status === SalesAction::STATUS_SAVE) {
    $statusLabel = 'warning';
} elseif ($model->status === SalesAction::STATUS_CLOSE) {
    $statusLabel = 'danger';
}
?>
  <div class="sales-action-update">

  <div class="text-right">
      <?php Box::begin() ?>
      <?= ActionButtons::widget([
          'order' => [['index', 'return']],
          'addReturnUrl' => false,
      ]) ?>
      <?php Box::end() ?>
  </div>
<?php echo $this->render('_form', compact('model')); ?>

<?php Box::begin() ?>
  <div class="row">
    <div class="col-md-6">
      <h4>Текущий статус акции:
        <span class="label label-<?= $statusLabel ?>"><?= SalesAction::getStatusAction()[$model->status] ?></span>
      </h4>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <h3>Основные параметры акции</h3><br/><br/>

    </div>
    <div class="col-md-6">
      <table class="table table-striped">
        <thead>
        <tr>
          <th>#</th>
          <th>Количество участников акции</th>
          <th>Добавление</th>
          <th>Просмотр</th>

        </tr>
        </thead>
        <tbody>
        <tr>
          <th scope="row">Участники</th>
          <th scope="row"><?= SalesAction::getCountUser($model->id) ?></th>
          <td><a href="/profiles/profile-action/index?action=<?= $model->id ?>" class="btn btn-primary">Пригласить
              участников</a></td>
          <td><a href="/profiles/sales-profile-action/index?action=<?= $model->id ?>" class="btn btn-primary">Просмотр
              участников</a></td>
        </tbody>
      </table>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <table class="table table-striped">
        <thead>
        <tr>
          <th>#</th>
          <th>Количество загруженных баллов за акцию</th>
          <th>Проверка загрузки</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <th scope="row">Бонусные баллы</th>
          <th scope="row"><?php if (Bonus::getBonusActionSum($model->id)) echo "<a target='_blank' href='/bonuses/bonus-action/index?action_id=" . $model->id . "'>" . Bonus::getBonusActionSum($model->id) . "</a>"; else echo 0; ?></th>
          <th scope="row"><a href="/bonuses/temp-bonuses/index" class="btn btn-primary">Проверка загруженных баллов</a>
        </tr>
        </tbody>
      </table>
    </div>
  </div>

  <hr>
  <div class="row">
    <div class="col-md-4">
      <h4>SMS приглашение</h4>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">

      <?= Html::a('Отправить смс приглашение ТОЛЬКО НОВЫМ участникам', [
          '/sales/sales-action/send-sms',
          'actionId' => $model->id,
          'type' => 'new'
      ], ['class' => 'btn btn-primary', 'role' => 'button']) ?>

        <?= Html::a('Отправить смс приглашение ВСЕМ участникам', [
            '/sales/sales-action/send-sms',
            'actionId' => $model->id,
            'type' => 'all'
        ], ['class' => 'btn btn-primary', 'role' => 'button']) ?>

      <hr>
      <h4>E-mail приглашение</h4>

        <?= Html::a('Отправить e-mail приглашение ТОЛЬКО НОВЫМ участникам', [
            '/sales/sales-action/send-email',
            'actionId' => $model->id,
            'type' => 'new'
        ], ['class' => 'btn btn-primary', 'role' => 'button']) ?>

        <?= Html::a('Отправить e-mail приглашение ВСЕМ участникам', [
            '/sales/sales-action/send-email',
            'actionId' => $model->id,
            'type' => 'all'
        ], ['class' => 'btn btn-primary', 'role' => 'button']) ?>

    </div>
  </div>

<?php
Box::end();
Box::begin();

if ($model->status === SalesAction::STATUS_SAVE || $model->status === SalesAction::STATUS_CLOSE): ?>
    <?= Html::a('НАЧАТЬ АКЦИЮ', ['/sales/sales-action/start', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
<?php elseif ($model->status === SalesAction::STATUS_CURRENT): ?>
    <?= Html::a('ЗАВЕРШИТЬ АКЦИЮ', ['/sales/sales-action/finish', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
<?php endif;
Box::end();