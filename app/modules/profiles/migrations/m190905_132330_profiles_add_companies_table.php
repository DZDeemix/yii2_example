<?php

use yii\db\Migration;

/**
 * Class m190905_132330_profiles_add_companies_table
 */
class m190905_132330_profiles_add_companies_table extends Migration
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

        $this->createTable('{{%companies}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addColumn('{{%dealers}}', 'company_id', $this->integer());

        $this->addForeignKey('fk_company_id', '{{%dealers}}', 'company_id', '{{%companies}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_company_id', '{{%dealers}}');
        $this->dropTable('{{%companies}}');
        $this->dropColumn('{{%dealers}}', 'company_id');

    }
}
