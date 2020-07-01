<?php

namespace modules\projects\backend\utils;

use marketingsolutions\finance\models\Purse;
use modules\profiles\common\models\Profile;
use modules\projects\common\models\Project;
use yii\db\Query;

class MultipurseColumn
{
    public static function get()
    {
        return [
            'label' => 'Кошельки',
            'contentOptions' => ['style' => 'text-align:center'],
            'format' => 'raw',
            'value' => function (Profile $model) {
                $purses = self::getPurses($model);
                if (empty($purses)) {
                    return '';
                }
                $html = [];
                foreach ($purses as $purse) {
                    $html[] = "<b>{$purse['title']}</b>: {$purse['balance']} <i class='fa fa-ruble-sign purse-balance'></i>";
                }

                return implode('<br/>', $html);
            }
        ];
    }
    
    public static function getPurses(Profile $model) {
        return (new Query())
            ->select(["purse.balance, purse.id, project.title, CONCAT(project.title, ' Баланс: ', purse.balance) as fullTitle"])
            ->from(['purse' => Purse::tableName()])
            ->innerJoin(['project' => Project::tableName()], 'project.id = purse.project_id')
            ->where([
                'owner_type' => Profile::class,
                'owner_id' => $model->id,
            ])
            ->andWhere(['<>', 'balance' , 0])
            ->all() ?? null;
    }
}
