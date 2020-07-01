<?php

use yii\db\Migration;

/**
 * Class m200603_110656_project_add_is_main_column
 */
class m200603_110656_project_add_is_main_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%projects}}', 'is_main', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%projects}}', 'is_main');
    }
}
