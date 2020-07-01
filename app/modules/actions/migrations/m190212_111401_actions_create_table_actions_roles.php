<?php

use yii\db\Migration;

/**
 * Class m190212_111401_actions_create_table_actions_roles
 */
class m190212_111401_actions_create_table_actions_roles extends Migration
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

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-actions_roles-actions}}', '{{%actions_roles}}');

        $this->dropTable('{{%actions_roles}}');
    }
}
