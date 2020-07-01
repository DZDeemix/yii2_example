<?php

namespace modules\actions\common\models;

use yz\interfaces\ModelInfoInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use modules\actions\common\models\Action;
use modules\profiles\common\models\Region;

/**
 * @property integer $id
 * @property integer $action_id
 * @property integer $region_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Action $action
 * @property Region $region
 */
class ActionRegion extends \yii\db\ActiveRecord implements ModelInfoInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%actions_regions}}';
    }

	/**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return 'Action Region';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Action Regions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['action_id', 'integer'],

            ['region_id', 'integer'],

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
            'region_id' => 'Регион',
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
    public function getRegion()
    {
        return $this->hasOne(Region::class, ['id' => 'region_id']);
    }
}
