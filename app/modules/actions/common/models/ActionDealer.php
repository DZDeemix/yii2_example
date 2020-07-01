<?php

namespace modules\actions\common\models;

use Yii;
use yz\interfaces\ModelInfoInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use modules\actions\common\models\Action;
use modules\profiles\common\models\Dealer;

/**
 * @property integer $id
 * @property integer $action_id
 * @property integer $dealer_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Action $action
 * @property Dealer $dealer
 */
class ActionDealer extends \yii\db\ActiveRecord implements ModelInfoInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%actions_dealers}}';
    }

	/**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return 'Action Dealer';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Action Dealers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['action_id', 'integer'],

            ['dealer_id', 'integer'],

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
            'dealer_id' => 'Дилер',
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
    public function getDealer()
    {
        return $this->hasOne(Dealer::class, ['id' => 'dealer_id']);
    }
}
