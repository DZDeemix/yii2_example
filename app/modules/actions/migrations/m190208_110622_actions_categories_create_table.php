<?php

use yii\db\Migration;

/**
 * Class m190208_110622_actions_categories_create_table
 */
class m190208_110622_actions_categories_create_table extends Migration
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

        $this->createTable('{{%actions_categories}}', [
            'id' => $this->primaryKey(),
            'action_id' => $this->integer(),
            'category_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-actions_categories-actions}}',
            '{{%actions_categories}}', 'action_id',
            '{{%actions}}', 'id',
            'RESTRICT', 'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-actions_categories-sales_categories}}',
            '{{%actions_categories}}', 'category_id',
            '{{%sales_categories}}', 'id',
            'RESTRICT', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-actions_categories-actions}}', '{{%actions_categories}}');
        $this->dropForeignKey('{{%fk-actions_categories-sales_categories}}', '{{%actions_categories}}');

        $this->dropTable('{{%actions_categories}}');
    }

}
