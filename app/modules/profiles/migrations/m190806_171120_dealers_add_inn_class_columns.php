<?php

use yii\db\Migration;

/**
 * Class m190806_171120_dealers_add_inn_class_columns
 */
class m190806_171120_dealers_add_inn_class_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%dealers}}', 'class', $this->string()->after('code'));
        $this->addColumn('{{%dealers}}', 'inn', $this->string()->after('code'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%dealers}}', 'class');
        $this->dropColumn('{{%dealers}}', 'inn');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190806_171120_dealers_add_inn_class_columns cannot be reverted.\n";

        return false;
    }
    */
}
