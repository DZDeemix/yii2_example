<?php

use yii\db\Migration;

/**
 * Class m190220_083516_action_add_column_status
 */
class m190220_083516_action_add_column_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%actions}}', 'status', $this->string(32)->after('role'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%actions}}', 'status');
    }
}
