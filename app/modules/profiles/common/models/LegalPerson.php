<?php

namespace modules\profiles\common\models;

use Yii;
use yii\db\Query;
use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_dealers".
 *
 * @property integer $id
 * @property string $name
 * @property string $project
 * @property string $contragent
 * @property string $site_id
 * @property string $class_name
 * @property string $created_at
 * @property string $updated_at

 */
class LegalPerson extends \yii\db\ActiveRecord implements ModelInfoInterface
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%legal_persons}}';
    }

    /**
     * Returns model title, ex.: 'Person', 'Book'
     *
     * @return string
     */
    public static function modelTitle()
    {
        return 'Юр лица';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     *
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Юр лица';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::class,
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'string', 'max' => 255],
            ['name', 'required'],
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

        ];
    }

    public function fields()
    {
        return [
            'id',
            'name',
        ];
    }


    public static function getOptions()
    {
        return LegalPerson::find()->select('name, id')->indexBy('id')->column();
    }

    public static function getNameOptions()
    {
        return self::find()->indexBy('name')->select('name')->orderBy(['name' => SORT_ASC])->column();
    }
}
