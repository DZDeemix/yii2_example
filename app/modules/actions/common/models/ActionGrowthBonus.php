<?php

namespace modules\actions\common\models;

use Yii;
use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_action_growth_bonus".
 *
 * @property integer $id
 * @property integer $action_id
 * @property integer $growth
 * @property integer $bonus
 */
class ActionGrowthBonus extends \yii\db\ActiveRecord implements ModelInfoInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%action_growth_bonus}}';
    }

	/**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return 'Action Growth Bonus';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Прирост и начисляемые бонусы по акции';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['action_id', 'integer'],
            ['growth_from', 'integer'],
            ['growth_to', 'integer'],
            ['bonus', 'integer']
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
            'bonus' => 'Бонусы',
            'growth_from' => 'Прирост от',
            'growth_to' => 'Прирост до',
        ];
    }

    public static function getGrowthBonus($id)
    {
        return self::find()->where(['action_id' => $id])->asArray()->all();
    }

    public function getAction()
    {
        return $this->hasOne(Action::className(), ['id' => 'action_id']);
    }

    public function getActionTitle()
    {
        return $this->action->title;
    }
}
