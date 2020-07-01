<?php

use yii\db\Migration;


class m200414_094944_projects_add_project_id_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableNames = [
            'finance_purses',
            'catalog_orders',
            'ordered_cards',
            'card_items',
            'payments',
        ];

        foreach ($tableNames as $tableName) {
            $prefixedTableName = "{{%{$tableName}}}";
            $table = Yii::$app->db->schema->getTableSchema($prefixedTableName);

            if ($table && !isset($table->columns['project_id'])) {
                $this->addColumn($prefixedTableName, 'project_id', $this->integer());
                $this->createIndex('idx_project_id', $prefixedTableName, 'project_id');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200225_094944_projects_add_project_id_column cannot be reverted.\n";

        return false;
    }
    */
}
