<?php

use yii\db\Migration;

class m181127_093224_actions_create_table_actions_profiles extends Migration
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

        $this->createTable('{{%actions_profiles}}', [
            'id' => $this->primaryKey(),
            'action_id' => $this->integer(),
            'profile_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-actions_profiles-actions}}',
            '{{%actions_profiles}}', 'action_id',
            '{{%actions}}', 'id',
            'RESTRICT', 'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-actions_profiles-dealers}}',
            '{{%actions_profiles}}', 'profile_id',
            '{{%profiles}}', 'id',
            'RESTRICT', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-actions_profiles-actions}}', '{{%actions_profiles}}');
        $this->dropForeignKey('{{%fk-actions_profiles-dealers}}', '{{%actions_profiles}}');

        $this->dropTable('{{%actions_profiles}}');
    }
}
