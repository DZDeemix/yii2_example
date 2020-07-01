<?php

use yii\db\Migration;

/**
 * Class m190227_080931_profiles_create_leaders_table
 */
class m190227_080931_profiles_create_leaders_table extends Migration
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

        $this->createTable('{{%legal_persons}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'project' => $this->string(),
            'contragent' => $this->string(),
            'site_id' => $this->string(),
            'class_name' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),


        ], $tableOptions);

        $this->createTable('{{%leaders}}', [
            'id' => $this->primaryKey(),
            'identity_id' => $this->integer(),
            'role' => $this->string(16),
            'first_name' => $this->string(32),
            'last_name' => $this->string(32),
            'middle_name' => $this->string(32),
            'full_name' => $this->string(),
            'phone_mobile' => $this->string(16),
            'email' => $this->string(128),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'legal_person_id' => $this->integer(),


        ], $tableOptions);

        $this->addForeignKey('{{%fk-leaders-admin_users}}',
            '{{%leaders}}', 'identity_id',
            '{{%admin_users}}', 'id',
            'RESTRICT', 'CASCADE');

        $this->addForeignKey('{{%fk-leaders-legal_persons}}',
            '{{%leaders}}', 'legal_person_id',
            '{{%legal_persons}}', 'id',
            'RESTRICT', 'CASCADE');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-leaders-admin_users}}', '{{%leaders}}');
        $this->dropForeignKey('{{%fk-leaders-legal_persons}}', '{{%leaders}}');

        $this->dropTable('{{%leaders}}');
        $this->dropTable('{{%legal_persons}}');
    }
}
