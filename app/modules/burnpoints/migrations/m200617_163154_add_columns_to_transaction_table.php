<?php

use yii\db\Migration;

/**
 * Class m200617_163154_add_columns_to_transaction_table
 */
class m200617_163154_add_columns_to_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%finance_transactions}}', 'points_to_burn', $this->bigInteger());
        $this->addColumn('{{%finance_transactions}}', 'residue', $this->bigInteger());
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%finance_transactions}}', 'points_to_burn');
        $this->dropColumn('{{%finance_transactions}}', 'residue');
    }
}
