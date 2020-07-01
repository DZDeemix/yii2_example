<?php

use yii\db\Migration;

/**
 * Class m200427_135330_actions_dealer_profiles_create_table
 */
class m200427_135330_actions_dealer_profiles_create_table extends Migration
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

        $this->createTable('{{%actions_dealer_profiles}}', [
            'id' => $this->primaryKey(),
            'action_id' => $this->integer(),
            'dealer_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'last_year_plan' => $this->integer()->defaultValue(0),
            'last_year_price_plan' => $this->integer()->defaultValue(0),
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-actions_dealer_profiles-actions}}',
            '{{%actions_dealer_profiles}}', 'action_id',
            '{{%actions}}', 'id',
            'RESTRICT', 'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-actions_dealer_profiles-dealers}}',
            '{{%actions_dealer_profiles}}', 'dealer_id',
            '{{%dealers}}', 'id',
            'RESTRICT', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-actions_dealer_profiles-actions}}', '{{%actions_dealer_profiles}}');
        $this->dropForeignKey('{{%fk-actions_dealer_profiles-dealers}}', '{{%actions_dealer_profiles}}');

        $this->dropTable('{{%actions_dealer_profiles}}');
    }
}
