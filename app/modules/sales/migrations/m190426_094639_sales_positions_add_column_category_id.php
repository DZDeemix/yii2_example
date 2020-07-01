<?php

use yii\db\Migration;

/**
 * Class m190426_094639_sales_positions_add_column_category_id
 */
class m190426_094639_sales_positions_add_column_category_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sales_positions}}', 'category_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%sales_positions}}', 'category_id');
    }

}
