<?php

use yii\db\Migration;

/**
 * Class m200610_101512_profiles_leaders_drop_key
 */
class m200610_101512_profiles_leaders_drop_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('{{%fk-leaders-legal_persons}}', '{{%leaders}}');
        $this->dropTable('{{%legal_persons}}');
        $this->addForeignKey('{{%fk-leaders-projects}}',
            '{{%leaders}}', 'legal_person_id',
            '{{%projects}}', 'id',
            'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200610_101512_profiles_leaders_drop_key cannot be reverted.\n";

        return false;
    }


}
