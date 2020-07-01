<?php

use yii\db\Migration;

/**
 * Class m200401_103110_actions_types_add_column_active
 */
class m200401_103110_actions_types_add_column_active extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%actions_types}}', 'active', $this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%actions_types}}', 'active');
    }


}
