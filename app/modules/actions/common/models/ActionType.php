<?php

namespace modules\actions\common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yz\interfaces\ModelInfoInterface;
use modules\actions\common\models\Action;

/**
 * This is the model class for table "yz_actions_types".
 *
 * @property integer $id
 * @property string $title
 * @property string $short_description
 * @property string $className
 * @property string $created_at
 * @property string $updated_at
 * @property intrger $active
 *
 * @property Action[] $actions
 */
class ActionType extends \yii\db\ActiveRecord implements ModelInfoInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%actions_types}}';
    }

	/**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return 'Тип акции';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Типы акций';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'filter', 'filter' => 'trim'],
            ['title', 'required'],
            ['title', 'string', 'max' => 255],

            ['short_description', 'required'],
            ['short_description', 'string', 'max' => 255],

            ['className', 'required'],
            ['className', 'string'],

            ['created_at', 'safe'],
            ['updated_at', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'short_description' => 'Короткое описание',
            'className' => 'Класс обработчик',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'active' => 'Доступно в списке',
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
     * @return array
     */
    public static function getList()
    {
        return self::find()->indexBy('id')->select('title, id')->orderBy(['id' => SORT_ASC])->column();
    }

    /**
     * @return array
     */
    public static function getListActive()
    {
        return self::find()->indexBy('id')->select('title, id')->where(["active"=>1])->orderBy(['id' => SORT_ASC])->column();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActions()
    {
        return $this->hasMany(Action::class, ['type_id' => 'id']);
    }
}
