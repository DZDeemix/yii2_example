<?php

$this->title = 'Регистрация покупок';
$this->params['header'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \modules\sales\frontend\widgets\AddSale::widget() ?>
<hr/>
<?= \modules\sales\frontend\widgets\SalesHistory::widget(['profile' => \Yii::$app->user->identity]) ?>
