<?php

use yii\db\Migration;

/**
 * Class m190212_132823_actions_create_table_actions_cities
 */
class m190212_132823_actions_create_table_actions_cities extends Migration
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

        $this->createTable('{{%actions_cities}}', [
            'id' => $this->primaryKey(),
            'action_id' => $this->integer(),
            'city_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-actions_cities-actions}}',
            '{{%actions_cities}}', 'action_id',
            '{{%actions}}', 'id',
            'RESTRICT', 'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-actions_cities-cities}}',
            '{{%actions_cities}}', 'city_id',
            '{{%cities}}', 'id',
            'RESTRICT', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-actions_cities-actions}}', '{{%actions_cities}}');
        $this->dropForeignKey('{{%fk-actions_cities-cities}}', '{{%actions_cities}}');

        $this->dropTable('{{%actions_cities}}');
    }
}
