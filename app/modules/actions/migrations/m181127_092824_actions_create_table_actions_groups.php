<?php

use yii\db\Migration;

class m181127_092824_actions_create_table_actions_groups extends Migration
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

        $this->createTable('{{%actions_groups}}', [
            'id' => $this->primaryKey(),
            'action_id' => $this->integer(),
            'group_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-actions_groups-actions}}',
            '{{%actions_groups}}', 'action_id',
            '{{%actions}}', 'id',
            'RESTRICT', 'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-actions_groups-sales_groups}}',
            '{{%actions_groups}}', 'group_id',
            '{{%sales_groups}}', 'id',
            'RESTRICT', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-actions_groups-actions}}', '{{%actions_groups}}');
        $this->dropForeignKey('{{%fk-actions_groups-sales_groups}}', '{{%actions_groups}}');

        $this->dropTable('{{%actions_groups}}');
    }
}
