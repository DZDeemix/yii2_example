<?php

use yii\db\Migration;

/**
 * Class m190424_103213_action_create_table_action_bonus
 */
class m190424_103213_action_create_table_action_bonus extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB CHARSET=utf8';
        }

        $this->createTable('{{%action_growth_bonus}}', [
            'id' => $this->primaryKey(),
            'action_id' => $this->integer(),
            'growth_from' => $this->integer(),
            'growth_to' => $this->integer(),
            'bonus' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('action_id', '{{%action_growth_bonus}}', 'action_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%action_growth_bonus}}');
    }
}
