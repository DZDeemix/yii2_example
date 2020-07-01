<?php


namespace modules\burnpoints\common\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yz\interfaces\ModelInfoInterface;

/**
 * Class BurnPoint
 *
 * @property int $id
 * @property int $profile_id
 * @property int $purse_id
 * @property int $amount
 * @property int $transaction_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @package modules\burnpoints\common\models
 */
class BurnPoint extends ActiveRecord implements ModelInfoInterface
{
    public static function tableName()
    {
        return '{{%burn_points}}';
    }

    /**
     * @inheritDoc
     */
    public static function modelTitle()
    {
        return 'Обнулениие баллов';
    }

    /**
     * @inheritDoc
     */
    public static function modelTitlePlural()
    {
        return 'Обнулениие баллов';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['profile_id', 'amount', 'transaction_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['profile_id', 'amount', 'transaction_id', 'purse_id'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'profile_id' => 'ID участника',
            'amount' => 'Сумма обнуления',
            'purse_id' => 'Кошелёк',
            'transaction_id' => 'ID транзакции',
            'created_at' => 'Дата созлания',
            'updated_at' => 'Дата обновления'
        ];
    }


}
