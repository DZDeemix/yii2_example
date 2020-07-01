<?php

use yii\db\Migration;

/**
 * Class m190523_104641_sales_reports_add_action_id_column
 */
class m190523_104641_sales_reports_add_action_id_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sales_reports}}', 'action_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%sales_reports}}', 'action_id');
    }

}
