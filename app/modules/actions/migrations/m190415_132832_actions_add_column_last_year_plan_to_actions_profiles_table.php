<?php

use yii\db\Migration;

/**
 * Class m190415_132832_actions_add_column_last_year_plan_to_actions_profiles_table
 */
class m190415_132832_actions_add_column_last_year_plan_to_actions_profiles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%actions_profiles}}', 'last_year_plan', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%actions_profiles}}', 'last_year_plan');
    }
}
