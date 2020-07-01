<?php

use yii\helpers\Html;
use modules\actions\common\models\Action;

/**
 * @var \yii\web\View $this;
 * @var \modules\actions\backend\models\ActionSearch $model
 */

?>

<?php if (Action::STATUS_NEW === $model->status) : ?>
    <?= Html::tag('div', Action::getStatusesList()[Action::STATUS_NEW], ['class' => 'label label-default']) ?>

    <?php if ($model->statusManager->canChangeStatus()) : ?>
        <?= Html::a('Активировать',
            ['/actions/action/set-status', 'id' => $model->id, 'value' => Action::STATUS_ACTIVE],
            [
                'class' => 'btn btn-xs btn-success mt-5',
                'title' => 'Акция станет доступна участникам',
                'data-confirm' => 'Вы уверены, что хотите запустить акцию?'
            ])
        ?>
    <?php endif; ?>

<?php endif; ?>


<?php if (Action::STATUS_ACTIVE === $model->status) : ?>
    <?= Html::tag('div', Action::getStatusesList()[Action::STATUS_ACTIVE], ['class' => 'label label-success']) ?>

    <?php if ($model->statusManager->canChangeStatus()) : ?>
        <div>
            <?php if ($model->statusManager->canUpdate()) : ?>
                <?= Html::a('Отключить',
                    ['/actions/action/set-status', 'id' => $model->id, 'value' => Action::STATUS_NEW],
                    [
                        'class' => 'btn btn-xs btn-default mt-5',
                        'title' => 'Вернуть акцию в статус "Новая"',
                        'data-confirm' => 'Вы уверены, что хотите вернуть акцию на доработку?'
                    ])
                ?>
            <?php endif ?>

            <?= Html::a('Завершить',
                ['/actions/action/set-status', 'id' => $model->id, 'value' => Action::STATUS_FINISHED],
                [
                    'class' => 'btn btn-xs btn-danger mt-5',
                    'title' => 'Завершить акцию досрочно',
                    'data-confirm' => 'Вы уверены, что хотите завершить акцию досрочно?'
                ])
            ?>

        </div>
    <?php endif ?>

<?php endif; ?>




<?php if (Action::STATUS_OLAP_CHECKED === $model->status) : ?>
    <?= Html::tag('div', Action::getStatusesList()[Action::STATUS_OLAP_CHECKED], ['class' => 'label label-info']) ?>

        <?= Html::a('Начислить бонусы',
            ['/actions/action/set-status', 'id' => $model->id, 'value' => Action::STATUS_PAID],
            [
                'class' => 'btn btn-xs btn-success mt-5',
                'title' => 'Начислить бонусы',
                'data-confirm' => 'Вы уверены, что хотите начислить бонусы по акции??'
            ])
        ?>

<?php endif; ?>


<?php if (Action::STATUS_PAID === $model->status) : ?>
    <?= Html::tag('div', Action::getStatusesList()[Action::STATUS_PAID], ['class' => 'label label-default']) ?>
<?php endif; ?>
