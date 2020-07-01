<?php

use yii\db\Migration;

/**
 * Class m190315_122952_actions_add_column_action_code_action_tbl
 */
class m190315_122952_actions_add_column_action_code_action_tbl extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%actions}}', 'code', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%actions}}', 'code');
    }
}
