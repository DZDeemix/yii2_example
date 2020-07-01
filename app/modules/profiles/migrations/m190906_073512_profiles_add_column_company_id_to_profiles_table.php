<?php

use yii\db\Migration;

/**
 * Class m190906_073512_profiles_add_column_company_id_to_profiles_table
 */
class m190906_073512_profiles_add_column_company_id_to_profiles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%profiles}}', 'company_id', $this->integer());
        $this->createIndex('company_id', '{{%profiles}}', 'company_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('company_id', '{{%profiles}}');
        $this->dropColumn('{{%profiles}}', 'company_id');
    }
}
