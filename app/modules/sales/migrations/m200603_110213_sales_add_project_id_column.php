<?php

use yii\db\Migration;

/**
 * Class m200603_110213_sales_add_project_id_column
 */
class m200603_110213_sales_add_project_id_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%sales}}', 'legal_person_id');
        $this->dropColumn('{{%sales_positions}}', 'legal_person_id');
    
        $this->addColumn('{{%sales}}', 'project_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%sales}}', 'legal_person_id', $this->integer());
        $this->addColumn('{{%sales_positions}}', 'legal_person_id', $this->integer());
    
        $this->dropColumn('{{%sales}}', 'project_id');
    }
}
