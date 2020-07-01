<?php

use yii\db\Migration;

/**
 * Class m190418_084401_action_add_action_type_data
 */
class m190418_084401_action_add_action_type_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $className = \modules\actions\common\types\SaleActionType::class;


        Yii::$app->db->createCommand()->batchInsert('{{%actions_types}}', ['id', 'title', 'short_description', 'className', 'created_at'], [
            [
                '10',
                'Простая акция с описанием',
                'Простая акция с описанием',
                $className,
                date("Y-m-d H:i:s")
            ],
        ])->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
