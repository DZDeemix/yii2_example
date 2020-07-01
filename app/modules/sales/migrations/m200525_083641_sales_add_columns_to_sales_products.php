<?php

use yii\db\Migration;

/**
 * Class m200525_083641_sales_add_columns_to_sales_products
 */
class m200525_083641_sales_add_columns_to_sales_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sales_positions}}', 'legal_person_id', $this->integer());
        $this->addColumn('{{%sales_products}}', 'role', $this->string());
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%sales_positions}}', 'legal_person_id');
        $this->dropColumn('{{%sales_products}}', 'role');
    }
    
}
