<?php

use yii\db\Migration;

/**
 * Class m190213_083122_actions_create_table_actions_admins
 */
class m190213_083122_actions_create_table_actions_admins extends Migration
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

        $this->createTable('{{%actions_admins}}', [
            'id' => $this->primaryKey(),
            'action_id' => $this->integer(),
            'auth_item_name' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-actions_admins-actions}}',
            '{{%actions_admins}}', 'action_id',
            '{{%actions}}', 'id',
            'RESTRICT', 'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-actions_admins-admin_auth_item}}',
            '{{%actions_admins}}', 'auth_item_name',
            '{{%admin_auth_item}}', 'name',
            'RESTRICT', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-actions_admins-actions}}', '{{%actions_admins}}');
        $this->dropForeignKey('{{%fk-actions_admins-admin_auth_item}}', '{{%actions_admins}}');

        $this->dropTable('{{%actions_admins}}');
    }
}
