<?php

use yii\db\Migration;

/**
 * Class m190213_073923_actions_add_column_role
 */
class m190213_073923_actions_add_column_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%actions}}', 'role', $this->string()->after('limit_qty'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%actions}}', 'role');
    }
}
