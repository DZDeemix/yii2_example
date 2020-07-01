<?php

use yii\db\Migration;

/**
 * Class m200220_142506_create_burnpoints_tables
 */
class m200220_142506_create_burnpoints_tables extends Migration
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

        $this->createTable('{{%burn_points_settings}}', [
            'id' => $this->primaryKey(),
            'warning_sms' => $this->boolean()->defaultValue(false),
            'warning_email' => $this->boolean()->defaultValue(false),
            'warning_push' => $this->boolean()->defaultValue(false),
            'days_to_burn' => $this->integer()->notNull()->defaultValue(365),
            'days_warning' => $this->integer()->notNull()->defaultValue(0),
            'count_warnings' => $this->integer()->notNull()->defaultValue(1),
            'sms_warning' => $this->string(500),
            'sms_nullify' => $this->string(500),
            'email_warning_subject' => $this->string(500),
            'email_warning_template' => $this->text(),
            'email_nullify_subject' => $this->string(500),
            'email_nullify_template' => $this->text(),
            'push_warning' => $this->string(500),
            'push_nullify' => $this->string(500),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->createTable('{{%burn_points}}', [
            'id' => $this->primaryKey(),
            'profile_id' => $this->integer()->unsigned(),
            'amount' => $this->integer()->notNull(),
            'transaction_id' => $this->integer()->unsigned(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->createIndex(
            'idx_burn_points_profile_id',
            '{{%burn_points}}',
            'profile_id'
        );

        $this->createIndex(
            'idx_burn_points_transaction_id',
            '{{%burn_points}}',
            'transaction_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%burn_points}}');

        $this->dropTable('{{%burn_points_settings}}');
    }
}
