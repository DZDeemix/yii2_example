<?php

use yii\db\Migration;

/**
 * Class m200521_110457_sale_add_legal_person_id_column
 */
class m200521_110457_sale_add_legal_person_id_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sales}}', 'legal_person_id', $this->integer());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%sales}}', 'legal_person_id');
    }

}
