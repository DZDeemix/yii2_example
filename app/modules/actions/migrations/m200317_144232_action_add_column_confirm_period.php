<?php

use yii\db\Migration;

/**
 * Class m200317_144232_action_add_column_confirm_period
 */
class m200317_144232_action_add_column_confirm_period extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%actions}}', 'confirm_period', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%actions}}', 'confirm_period');
    }
}
