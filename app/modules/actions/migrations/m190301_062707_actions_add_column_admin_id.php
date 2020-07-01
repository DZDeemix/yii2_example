<?php

use yii\db\Migration;

class m190301_062707_actions_add_column_admin_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%actions}}', 'admin_id', $this->integer()->after('id'));

        $this->addForeignKey(
            '{{%fk-actions-admin_users}}',
            '{{%actions}}', 'admin_id',
            '{{%admin_users}}', 'id',
            'RESTRICT', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-actions-admin_users}}', '{{%actions}}');

        $this->dropColumn('{{%actions}}', 'admin_id');
    }
}
