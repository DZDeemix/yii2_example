<?php

use yii\db\Migration;

/**
 * Class m200618_162510_add_purse_id_to_burnpoints
 */
class m200618_162510_add_purse_id_to_burnpoints extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%burn_points}}', 'purse_id', $this->integer());
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%burn_points}}', 'purse_id');
    }
    
    
}
