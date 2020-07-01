<?php

use yii\db\Migration;

/**
 * Class m190206_122409_actions_add_column_bonuses_amount
 */
class m190206_122409_actions_add_column_bonuses_amount extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("{{%actions}}", 'bonuses_amount', $this->integer()->after('bonuses_formula'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%actions}}", 'bonuses_amount');
    }
}
