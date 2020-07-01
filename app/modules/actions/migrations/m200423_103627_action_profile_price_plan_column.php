<?php

use yii\db\Migration;

/**
 * Class m200423_103627_action_profile_price_plan_column
 */
class m200423_103627_action_profile_price_plan_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%actions_profiles}}', 'last_year_price_plan', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%actions_profiles}}', 'last_year_price_plan');
    }
}
