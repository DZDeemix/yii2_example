<?php

use yii\db\Migration;

/**
 * Class m200525_134425_add_address_to_sales_table
 */
class m200525_134425_add_address_to_sales_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sales}}', 'address', $this->string());
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%sales}}', 'address');
    }
    
}
