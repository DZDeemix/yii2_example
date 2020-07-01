<?php

use yii\db\Migration;

/**
 * Class m200525_114526_add_is_main_to_legal_persons_table
 */
class m200525_114526_add_is_main_to_legal_persons_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%legal_persons}}', 'is_main', $this->boolean());
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%legal_persons}}', 'is_main');
    }
    
}
