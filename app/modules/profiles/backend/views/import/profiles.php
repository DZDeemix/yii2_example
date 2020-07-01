<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yz\icons\Icons;

$this->title = 'Загрузка участников';
$this->params['header'] = $this->title;
?>

<div class="row">
    <div class="col-sm-offset-2 col-sm-8">
        <a href="/example/profiles.xlsx">
            <i class="fa fa-file-excel"></i>
            Скачать пример файла для загрузки участников
        </a>
    </div>
</div>