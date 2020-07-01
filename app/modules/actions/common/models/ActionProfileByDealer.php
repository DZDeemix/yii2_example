<?php

namespace modules\actions\common\models;

use modules\actions\common\models\Action;
use modules\profiles\common\models\Dealer;
use Yii;
use yz\interfaces\ModelInfoInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use modules\profiles\common\models\Profile;

/**
 * This is the model class for table "yz_actions_products".
 *
 * @property integer $id
 * @property integer $action_id
 * @property integer $dealer_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $last_year_plan
 * @property string $last_year_price_plan
 *
 * @property Action $action
 * @property Dealer $dealer
 */
class   ActionProfileByDealer extends \yii\db\ActiveRecord implements ModelInfoInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%actions_dealer_profiles}}';
    }

	/**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return 'Индивидуальные планы по акции по Аптекам';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Индивидуальный план по акции по Аптекам';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['action_id', 'integer'],
            ['dealer_id', 'integer'],
            ['last_year_plan', 'integer'],
            ['last_year_price_plan', 'integer'],
            ['created_at', 'safe'],
            ['updated_at', 'safe'],
            ['updated_at', 'safe'],
            ['dealer_info', 'safe'],
          //  ['profile_id', 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            ['action_id', 'integer'],
            ['dealer_id', 'integer'],
            ['last_year_plan', 'integer'],
            ['last_year_price_plan', 'integer'],
            ['created_at', 'safe'],
            ['updated_at', 'safe'],
            ['dealer_info', 'safe'],
           // ['profile_id', 'integer']
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
            'dealer_id' => 'N',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'last_year_plan' => 'План в штуках',
            'last_year_price_plan' => 'План в рублях',
            'dealer_info' => 'Аптека',
           //'profile_id' => 'Участник',

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


    public function getDealer_info()
    {
        return $this->dealer->external_id."; ".$this->dealer->name."; ".$this->dealer->address;
    }




}
