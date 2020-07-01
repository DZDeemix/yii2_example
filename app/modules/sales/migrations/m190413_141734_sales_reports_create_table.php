<?php

use yii\db\Migration;

/**
 * Class m190413_141734_sales_reports_create_table
 */
class m190413_141734_sales_reports_create_table extends Migration
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

        $this->createTable('{{%sales_reports}}', [
            'id' => $this->primaryKey(),
            'report' => $this->string(),
            'profile_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        @$this->dropTable('{{%sales_reports}}');
    }

}
