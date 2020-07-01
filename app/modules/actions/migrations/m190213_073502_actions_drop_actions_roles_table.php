<?php

use yii\db\Migration;

/**
 * Class m190213_073502_actions_drop_actions_roles_table
 */
class m190213_073502_actions_drop_actions_roles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('{{%fk-actions_roles-actions}}', '{{%actions_roles}}');

        $this->dropTable('{{%actions_roles}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB CHARSET=utf8';
        }

        $this->createTable('{{%actions_roles}}', [
            'id' => $this->primaryKey(),
            'action_id' => $this->integer(),
            'role' => $this->string(16),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-actions_roles-actions}}',
            '{{%actions_roles}}', 'action_id',
            '{{%actions}}', 'id',
            'RESTRICT', 'CASCADE'
        );
    }
}
