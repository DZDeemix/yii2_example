<?php

use yii\db\Migration;

class m180330_094236_sales_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB CHARSET=utf8';
        }

        $this->createTable('{{%sales_categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable('{{%sales_groups}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable('{{%sales_products}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'group_id' => $this->integer(),
            'name' => $this->string(),
            'code' => $this->string(),
            'name_html' => $this->string(500),
            'title' => $this->string(500),
            'description' => $this->text(),
            'url' => $this->string(500),
            'photo_name' => $this->string(),
            'price' => $this->integer(),
            'weight' => $this->string(),
            'unit_id' => $this->integer(),
            'bonuses_formula' => $this->string(),
            'url_shop' => $this->string(500),
            'is_show_on_main' => $this->boolean()->defaultValue(false),
            'enabled' => $this->boolean()->defaultValue(true),
        ], $tableOptions);
        $this->createIndex('category_id', '{{%sales_products}}', 'category_id');
        $this->createIndex('group_id', '{{%sales_products}}', 'group_id');
        $this->createIndex('unit_id', '{{%sales_products}}', 'unit_id');

        $this->createTable('{{%sales_units}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'short_name' => $this->string(),
            'quantity_divider' => $this->integer()->defaultValue(1),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable('{{%sales}}', [
            'id' => $this->primaryKey(),
            'status' => $this->string(),
            'number' => $this->string(),
            'recipient_id' => $this->integer(),
            'total_cost' => $this->integer(),
            'bonuses' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'sold_on' => $this->dateTime(),
            'approved_by_admin_at' => $this->dateTime(),
            'bonuses_paid_at' => $this->dateTime(),
            'sale_products' => $this->string(1000),
            'sale_groups' => $this->string(1000),
            'sale_qty' => $this->string(1000),
            'place' => $this->string(1000),
        ], $tableOptions);
        $this->createIndex('recipient_id', '{{%sales}}', 'recipient_id');

        $this->createTable('{{%sales_positions}}', [
            'id' => $this->primaryKey(),
            'sale_id' => $this->integer(),
            'product_id' => $this->integer(),
            'quantity' => $this->integer(),
            'bonuses' => $this->integer(),
            'cost' => $this->integer(),
            'bonuses_primary' => $this->integer(),
        ], $tableOptions);
        $this->createIndex('sale_id', '{{%sales_positions}}', 'sale_id');
        $this->createIndex('product_id', '{{%sales_positions}}', 'product_id');

        $this->createTable('{{%sales_validation_rules}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(128),
            'is_enabled' => $this->boolean(),
            'rule' => $this->string(255),
            'error' => $this->string(255),
        ], $tableOptions);

        $this->createTable('{{%sales_history}}', [
            'id' => $this->primaryKey(),
            'sale_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'status_old' => $this->string(),
            'status_new' => $this->string(),
            'comment' => $this->text(),
            'note' => $this->text(),
            'admin_id' => $this->integer(),
            'role' => $this->string(),
            'type' => $this->string(),
        ], $tableOptions);
        $this->createIndex('admin_id', '{{%sales_history}}', 'admin_id');
        $this->createIndex('sale_id', '{{%sales_history}}', 'sale_id');

        ////////////////////////

        $this->addForeignKey('{{%fk-sales-products-units}}', '{{%sales_products}}', 'unit_id', '{{%sales_units}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%fk-sales-products-categories}}', '{{%sales_products}}', 'category_id', '{{%sales_categories}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%fk-sales-products-groups}}', '{{%sales_products}}', 'group_id', '{{%sales_groups}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%fk-sales-positions-products}}', '{{%sales_positions}}', 'product_id', '{{%sales_products}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('{{%fk-sales-positions-sales}}', '{{%sales_positions}}', 'sale_id', '{{%sales}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%fk-sales-history-sales}}', '{{%sales_history}}', 'sale_id', '{{%sales}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        @$this->dropTable('{{%sales}}');
        @$this->dropTable('{{%sales_history}}');
        @$this->dropTable('{{%sales_positions}}');
        @$this->dropTable('{{%sales_units}}');
        @$this->dropTable('{{%sales_products}}');
        @$this->dropTable('{{%sales_groups}}');
        @$this->dropTable('{{%sales_categories}}');
        @$this->dropTable('{{%sales_validation_rules}}');
    }
}
