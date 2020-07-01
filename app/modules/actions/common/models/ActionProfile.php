<?php

namespace modules\actions\common\models;

use Yii;
use yz\interfaces\ModelInfoInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use modules\actions\common\models\Action;
use modules\profiles\common\models\Profile;

/**
 * This is the model class for table "yz_actions_products".
 *
 * @property integer $id
 * @property integer $action_id
 * @property integer $profile_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $last_year_plan
 * @property string $last_year_price_plan
 *
 * @property Action $action
 * @property Profile $profile
 */
class   ActionProfile extends \yii\db\ActiveRecord implements ModelInfoInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%actions_profiles}}';
    }

	/**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return 'Action Profile';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Индивидуальный план по акции';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['action_id', 'integer'],
            ['profile_id', 'integer'],
            ['last_year_plan', 'integer'],
            ['last_year_price_plan', 'integer'],
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
            'profile_id' => 'Участник',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'last_year_plan' => 'План в штуках',
            'last_year_price_plan' => 'План в рублях',

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
    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['id' => 'profile_id']);
    }

    public function getProfileFullname()
    {
        return $this->profile->full_name;
    }

    public function getPhoneMobile()
    {
        return $this->profile->phone_mobile;
    }

    public function getRole()
    {
        return $this->profile->role;
    }

}
