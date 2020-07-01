<?php

use yii\db\Migration;

/**
 * Class m200604_090947_sales_add_role_column
 */
class m200604_090947_sales_add_role_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sales}}', 'role', $this->string());
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%sales}}', 'role');
    }
}
