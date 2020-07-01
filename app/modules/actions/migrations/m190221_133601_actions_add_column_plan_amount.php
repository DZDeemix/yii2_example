<?php

use yii\db\Migration;

/**
 * Class m190221_133601_actions_add_column_plan_amount
 */
class m190221_133601_actions_add_column_plan_amount extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%actions}}', 'plan_amount', $this->integer()->after('bonuses_amount'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%actions}}', 'plan_amount');
    }
}
