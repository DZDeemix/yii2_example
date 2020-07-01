<?php

use yii\db\Migration;

/**
 * Class m190221_132155_action_has_products
 */
class m190221_132155_action_has_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%actions}}', 'has_products', $this->boolean()->defaultValue(true)->after('limit_qty'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%actions}}', 'has_products');
    }
}
