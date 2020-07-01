<?php

use yii\db\Migration;

/**
 * Class m200603_111026_drop_is_main_to_legal_persons_table
 */
class m200603_111026_drop_is_main_to_legal_persons_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%legal_persons}}', 'is_main');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%legal_persons}}', 'is_main', $this->boolean());
    }
}
