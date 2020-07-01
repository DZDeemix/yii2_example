<?php

use yii\db\Migration;

/**
 * Class m200427_204740_actions_add_send_email_column
 */
class m200427_204740_actions_add_send_email_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%actions}}', 'email_is_send', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%actions}}', 'email_is_send');
    }
}
