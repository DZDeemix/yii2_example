<?php

use yii\db\Migration;

/**
 * Class m190417_061227_action_add_column_personal_plan_to_action_table
 */
class m190417_061227_action_add_column_personal_plan_to_action_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%actions}}', 'personal_plan_formula', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%actions}}', 'personal_plan_formula');
    }
}
