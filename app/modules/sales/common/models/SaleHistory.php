<?php

namespace modules\sales\common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_sales_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $status_old
 * @property string $status_new
 * @property string $comment
 * @property string $note
 * @property integer $admin_id
 * @property integer $sale_id
 * @property string $role
 * @property string $type
 *
 * @property Sale $sale
 */
class SaleHistory extends \yii\db\ActiveRecord implements ModelInfoInterface
{
    const TYPE_EMAIL = 'email';
    const TYPE_CREATE = 'create';
    const TYPE_UPDATE = 'update';
    const TYPE_DECLINE = 'decline';
    const TYPE_DRAFT = 'draft';
    const TYPE_APPROVE = 'approve';
    const TYPE_PAY = 'pay';
    const TYPE_ROLLBACK = 'rollback';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sales_history}}';
    }

	/**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return 'Sale History';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Sale Histories';
    }

    public function beforeSave($insert)
    {
        $now = (new \DateTime('now'))->format('Y-m-d H:i:s');
        if ($insert) {
            $this->created_at = $now;
        }
        $this->updated_at = $now;

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['created_at', 'safe'],
            ['updated_at', 'safe'],
            ['status_old', 'string'],
            ['status_new', 'string'],
            ['comment', 'string'],
            ['note', 'string'],
            ['role', 'string'],
            ['type', 'string'],
            ['sale_id', 'integer'],
            ['admin_id', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status_old' => 'Status Old',
            'status_new' => 'Status New',
            'comment' => 'Comment',
            'note' => 'Comment Text',
            'role' => 'Role',
            'type' => 'Type',
            'sale_id' => 'Sale ID',
            'leader_id' => 'Leader ID',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'created_at' => function (SaleHistory $model) {
                return empty($model->created_at) ? null : (new \DateTime($model->created_at))->format('d.m.Y H:s');
            },
            'note',
            'comment',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSale()
    {
        return $this->hasOne(Sale::class, ['id' => 'sale_id']);
    }
}
