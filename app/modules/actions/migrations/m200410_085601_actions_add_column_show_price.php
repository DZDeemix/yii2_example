<?php

use yii\db\Migration;

/**
 * Class m200410_085601_actions_add_column_show_price
 */
class m200410_085601_actions_add_column_show_price extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%actions}}', 'show_price', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%actions}}', 'show_price');
    }
}
