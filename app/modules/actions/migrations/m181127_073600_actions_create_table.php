<?php

use yii\db\Migration;

class m181127_073600_actions_create_table extends Migration
{
    public function up()
    {

        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        @$this->dropTable('{{%actions_dealers}}');
        @$this->dropTable('{{%actions_positions_products}}');
        @$this->dropTable('{{%actions_positions}}');
        @$this->dropTable('{{%actions_profiles}}');
        @$this->dropTable('{{%actions}}');

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB CHARSET=utf8';
        }

        $this->createTable('{{%actions_types}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'short_description' => $this->string(),
            'className' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable('{{%actions}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'start_on' => $this->date(),
            'end_on' => $this->date(),
            'type_id' => $this->integer(),
            'short_description' => $this->string(),
            'description' => $this->text(),
            'bonuses_formula' => $this->string(),
            'pay_type' => $this->string(16),
            'pay_threshold' => $this->integer(),
            'limit_bonuses' => $this->integer(),
            'limit_qty' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-actions-actions_types}}',
            '{{%actions}}', 'type_id',
            '{{%actions_types}}', 'id',
            'RESTRICT', 'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('{{%fk-actions-actions_types}}', '{{%actions}}');

        $this->dropTable('{{%actions}}');
        $this->dropTable('{{%actions_types}}');
    }
}
