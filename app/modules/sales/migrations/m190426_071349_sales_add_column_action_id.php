<?php

use yii\db\Migration;

/**
 * Class m190426_071349_sales_add_column_action_id
 */
class m190426_071349_sales_add_column_action_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sales}}', 'action_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%sales}}', 'action_id');
    }

}
