<?php if(!\Yii::$app->request->get('id')): ?>
    <div class="row">
        <div class="col-sm-offset-2">
            &nbsp;&nbsp;&nbsp;<a class="btn btn-success" href="/actions/action/index">
                <i class="-anchor"></i>Вернуться к выбору акции для загрузки из списка
            </a>
            <a class="btn btn-success" href="/actions/action-profile/refresh">
                <i class="-refresh"></i>&nbsp;&nbsp;Найти и обновить участников
            </a>
        </div>
    </div>
<?php endif;?>

