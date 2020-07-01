<?php

use yii\db\Migration;

/**
 * Class m200514_145332_profiles_add_columns_company_name_social_link
 */
class m200514_145332_profiles_add_columns_company_name_social_link extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%profiles}}', 'company_name', $this->string());
        $this->addColumn('{{%profiles}}', 'social_link', $this->string());
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%profiles}}', 'company_name');
        $this->dropColumn('{{%profiles}}', 'social_link');
    }
}
