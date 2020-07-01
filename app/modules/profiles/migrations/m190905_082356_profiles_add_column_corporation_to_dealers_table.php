<?php

use yii\db\Migration;

/**
 * Class m190905_082356_profiles_add_column_corporation_to_dealers_table
 */
class m190905_082356_profiles_add_column_corporation_to_dealers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%dealers}}', 'corporation', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%dealers}}', 'corporation');
    }
}
