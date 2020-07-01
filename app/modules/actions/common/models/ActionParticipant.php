<?php

namespace modules\actions\common\models;

use modules\profiles\common\models\Profile;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_actions_products".
 *
 * @property integer $id
 * @property integer $action_id
 * @property integer $profile_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Action $action
 * @property Profile $profile
 */
class   ActionParticipant extends \yii\db\ActiveRecord implements ModelInfoInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%actions_participants}}';
    }

    /**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return 'Action Participant';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Заявленные участники по акции';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['action_id', 'integer'],
            ['profile_id', 'integer'],

        ];
    }

    public function attributes()
    {
        return ['created_at', 'id','action_id','profile_id','updated_at'];
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


    /**
     * @return ActiveQuery
     */
    public function getSale_points()
    {
        return $this->profile->getSale_points();
    }

    /**
     * @return ActiveQuery
     */
    public function getSales()
    {
        return $this->action->getSales()->filterHaving(["recipient_id" => $this->profile_id]);
    }


    public function getRole()
    {
        return $this->profile->role;
    }

}
