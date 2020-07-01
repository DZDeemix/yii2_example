<?php

use yii\db\Migration;

/**
 * Class m190515_073444_profile_add_filter_columns
 */
class m190515_073444_profile_add_filter_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%profiles}}', 'is_checked', $this->boolean()->defaultValue(false));
        $this->addColumn('{{%profiles}}', 'is_blocked', $this->boolean()->defaultValue(false));
        $this->addColumn('{{%profiles}}', 'is_banned', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%profiles}}', 'is_checked');
        $this->dropColumn('{{%profiles}}', 'is_blocked');
        $this->dropColumn('{{%profiles}}', 'is_banned');
    }
}
