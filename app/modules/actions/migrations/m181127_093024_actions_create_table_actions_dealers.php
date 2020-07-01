<?php

use yii\db\Migration;

class m181127_093024_actions_create_table_actions_dealers extends Migration
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

        $this->createTable('{{%actions_dealers}}', [
            'id' => $this->primaryKey(),
            'action_id' => $this->integer(),
            'dealer_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-actions_dealers-actions}}',
            '{{%actions_dealers}}', 'action_id',
            '{{%actions}}', 'id',
            'RESTRICT', 'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-actions_dealers-dealers}}',
            '{{%actions_dealers}}', 'dealer_id',
            '{{%dealers}}', 'id',
            'RESTRICT', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-actions_dealers-actions}}', '{{%actions_dealers}}');
        $this->dropForeignKey('{{%fk-actions_dealers-dealers}}', '{{%actions_dealers}}');

        $this->dropTable('{{%actions_dealers}}');
    }
}
