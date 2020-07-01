<?php

use yii\db\Migration;

/**
 * Class m200525_123210_add_comment_to_sales_table
 */
class m200525_123210_add_comment_to_sales_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sales}}', 'comment', $this->text());
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%sales}}', 'comment');
    }
    
}
