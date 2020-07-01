<?php

namespace modules\profiles\common\models;

use Yii;
use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_companies".
 *
 * @property integer $id
 * @property integer $name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Dealers[] $dealers
 */
class Companies extends \yii\db\ActiveRecord implements ModelInfoInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%companies}}';
    }

	/**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return 'Companies';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Компании';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'string'],
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
            'name' => 'Наименование компании',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDealers()
    {
        return $this->hasMany(Dealer::className(), ['company_id' => 'id']);
    }

    public static function optionValue()
    {
        return Companies::find()->select('name')->indexBy('id')->column();
    }

    public static function getCompanyValues()
    {
        return self::find()->select('name')->indexBy('id')->column();
    }
}
