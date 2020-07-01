<?php

use modules\projects\common\models\Project;
use yii\db\Migration;

/**
 * Class m200225_083124_projects_init
 */
class m200225_083124_projects_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB CHARSET=utf8';
        }

        $this->createTable('{{%projects}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->unique()->notNull(),
            'id1c' => $this->string(40),
            'is_enabled' => $this->boolean()->defaultValue(true),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createIndex('idx_enabled', '{{%projects}}', 'is_enabled');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%projects}}');
    }
}
