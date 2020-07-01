<?php

use yii\db\Migration;

/**
 * Class m180323_102508_profiles_init
 */
class m180323_102508_profiles_init extends Migration
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

        $this->createTable('{{%profiles}}', [
            'id' => $this->primaryKey(),
            'passhash' => $this->string(),
            'first_name' => $this->string(32),
            'last_name' => $this->string(32),
            'middle_name' => $this->string(32),
            'full_name' => $this->string(),
            'phone_mobile' => $this->string(16)->unique(),
            'email' => $this->string(64),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'birthday_on' => $this->date(),
            'dealer_id' => $this->integer(),
            'city_id' => $this->integer(),
            'region_id' => $this->integer(),
            'avatar' => $this->string(),
            'gender' => $this->string(),
            'role' => $this->string(),
            'specialty' => $this->string(),
            'document' => $this->string(500),
            'blocked_at' => $this->dateTime(),
            'blocked_reason' => $this->text(),
            'banned_at' => $this->dateTime(),
            'banned_reason' => $this->text(),
            'phone_confirmed_at' => $this->dateTime(),
            'email_confirmed_at' => $this->dateTime(),
            'uniqid' => $this->string(50),
            'registered_at' => $this->dateTime(),
            'checked_at' => $this->dateTime(),
            'is_uploaded' => $this->boolean()->defaultValue(false),
            'external_id' => $this->integer(),
            'external_token' => $this->string(),
            'pers_at' => $this->dateTime(),
        ], $tableOptions);
        $this->createIndex('dealer_id', '{{%profiles}}', 'dealer_id');
        $this->createIndex('city_id', '{{%profiles}}', 'city_id');
        $this->createIndex('region_id', '{{%profiles}}', 'region_id');

        $this->createTable('{{%dealers}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'address' => $this->string(),
            'leader_id' => $this->integer(),
            'city_id' => $this->integer(),
            'region_id' => $this->integer(),
            'code' => $this->string(50),
            'type' => $this->string(20),
            'document' => $this->string(500),
            'external_id' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);
        $this->createIndex('leader_id', '{{%dealers}}', 'leader_id');
        $this->createIndex('city_id', '{{%dealers}}', 'city_id');
        $this->createIndex('region_id', '{{%dealers}}', 'region_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropTable('{{%profiles}}');
        $this->dropTable('{{%dealers}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180323_102508_profiles_init cannot be reverted.\n";

        return false;
    }
    */
}
