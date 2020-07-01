<?php

use yii\db\Migration;

/**
 * Class m200225_113913_actions_create_table_actions_participant
 */
class m200225_113913_actions_create_table_actions_participant extends Migration
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

        $this->createTable('{{%actions_participants}}', [
            'id' => $this->primaryKey(),
            'action_id' => $this->integer(),
            'profile_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-actions_participants-actions}}',
            '{{%actions_participants}}', 'action_id',
            '{{%actions}}', 'id',
            'RESTRICT', 'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-actions_participants-dealers}}',
            '{{%actions_participants}}', 'profile_id',
            '{{%profiles}}', 'id',
            'RESTRICT', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-actions_participants-actions}}', '{{%actions_profiles}}');
        $this->dropForeignKey('{{%fk-actions_participants-dealers}}', '{{%actions_profiles}}');

        $this->dropTable('{{%actions_participants}}');
    }
}
