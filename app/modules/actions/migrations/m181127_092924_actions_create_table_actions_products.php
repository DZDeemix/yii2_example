<?php

use yii\db\Migration;

class m181127_092924_actions_create_table_actions_products extends Migration
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

        $this->createTable('{{%actions_products}}', [
            'id' => $this->primaryKey(),
            'action_id' => $this->integer(),
            'product_id' => $this->integer(),
            'bonus' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-actions_products-actions}}',
            '{{%actions_products}}', 'action_id',
            '{{%actions}}', 'id',
            'RESTRICT', 'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-actions_products-sales_products}}',
            '{{%actions_products}}', 'product_id',
            '{{%sales_products}}', 'id',
            'RESTRICT', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-actions_products-actions}}', '{{%actions_products}}');
        $this->dropForeignKey('{{%fk-actions_products-sales_products}}', '{{%actions_products}}');

        $this->dropTable('{{%actions_products}}');
    }
}
