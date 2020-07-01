<?php

namespace modules\actions\common\models;

use Yii;
use yz\interfaces\ModelInfoInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use modules\actions\common\models\Action;
use modules\sales\common\models\Product;

/**
 * This is the model class for table "yz_actions_products".
 *
 * @property integer $id
 * @property integer $action_id
 * @property integer $product_id
 * @property integer $bonus
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Action $action
 * @property Product $product
 */
class ActionProduct extends \yii\db\ActiveRecord implements ModelInfoInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%actions_products}}';
    }

	/**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return 'Продукты для акции';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Продукты для акции';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['action_id', 'integer'],
            ['product_id', 'integer'],
            ['bonus', 'integer'],
            ['created_at', 'safe'],
            ['updated_at', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action_id' => 'Акция',
            'product_id' => 'Товар',
            'bonus' => 'Кол-во. бонусов',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAction()
    {
        return $this->hasOne(Action::class, ['id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * @return array
     */

}
