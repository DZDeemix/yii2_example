<?php

use yii\db\Migration;

/**
 * Class m190212_132815_actions_create_table_actions_regions
 */
class m190212_132815_actions_create_table_actions_regions extends Migration
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

        $this->createTable('{{%actions_regions}}', [
            'id' => $this->primaryKey(),
            'action_id' => $this->integer(),
            'region_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-actions_regions-actions}}',
            '{{%actions_regions}}', 'action_id',
            '{{%actions}}', 'id',
            'RESTRICT', 'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-actions_regions-regions}}',
            '{{%actions_regions}}', 'region_id',
            '{{%regions}}', 'id',
            'RESTRICT', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-actions_regions-actions}}', '{{%actions_regions}}');
        $this->dropForeignKey('{{%fk-actions_regions-regions}}', '{{%actions_regions}}');

        $this->dropTable('{{%actions_regions}}');
    }
}
