<?php

namespace modules\sales\common\models;

use modules\profiles\common\models\Dealer;
use modules\profiles\common\models\Profile;
use modules\sales\common\sales\bonuses\BonusesCalculator;
use modules\sales\common\sales\bonuses\FormulaValidator;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_sales_products".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $group_id
 * @property string $name
 * @property string $name_html
 * @property string $code
 * @property string $title
 * @property string $description
 * @property string $url
 * @property string $url_shop
 * @property string $photo_name
 * @property integer $price
 * @property string $weight
 * @property integer $unit_id
 * @property string $bonuses_formula
 * @property boolean $enabled
 * @property boolean $is_show_on_main
 * @property boolean $role
 *
 * @property Category $category
 * @property Group $group
 * @property Unit $unit
 * @property BonusesCalculator $bonusesCalculator
 * @property string $photo_url
 */
class Product extends \yz\db\ActiveRecord implements ModelInfoInterface
{
    public $photo;

    /**
     * @var BonusesCalculator
     */
    private $bonusesCalculator;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sales_products}}';
    }

    /**
     * Returns model title, ex.: 'Person', 'Book'
     *
     * @return string
     */
    public static function modelTitle()
    {
        return 'Товар';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     *
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Продукция';
    }

    public static function getUnitIdValues()
    {
        return Unit::find()->select('name, id')->indexBy('id')->column();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'group_id', 'unit_id', 'price', 'weight'], 'integer'],
            [['name', 'bonuses_formula'], 'string', 'max' => 255],
            [['name_html', 'code', 'title', 'description', 'photo_name', 'url', 'url_shop'], 'string'],
            [['url', 'url_shop'], 'url'],
            ['name', 'required'],
            ['enabled', 'safe'],
            ['bonuses_formula', 'required'],
//            ['bonuses_formula', FormulaValidator::className()],
            ['is_show_on_main', 'boolean'],
            ['is_show_on_main', 'default', 'value' => false],
            ['role', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'group_id' => 'Подразделение',
            'name' => 'Название',
            'name_html' => 'Название HTML',
            'code' => 'Код продукции',
            'title' => 'Описание',
            'description' => 'Описание полное',
            'url' => 'Ссылка на описание продукции',
            'url_shop' => 'Ссылка на магазин продукции',
            'weight' => 'Вес/объем',
            'price' => 'Цена',
            'photo' => 'Изображение',
            'photo_name' => 'Изображение',
            'unit_id' => 'Единица измерения',
            'bonuses_formula' => 'Формула для бонусов',
            'enabled' => 'Доступен',
            'is_show_on_main' => 'Показать в слайдере на главной странице',
            'role' => 'Товар для роли'
        ];
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'photo_url',
            'bonuses_formula',
            'role',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit_id']);
    }

    public function getBonusesCalculator()
    {
        if ($this->bonusesCalculator === null) {
            $this->bonusesCalculator = new BonusesCalculator($this);
        }
        return $this->bonusesCalculator;
    }

    public static function getOptions()
    {
        return self::find()->indexBy('id')->select('name, id')->orderBy(['name' => SORT_ASC])->column();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->upload();
    }

    public function getPhotoPath()
    {
        return Yii::getAlias('@frontendWebroot/data/products/' . $this->photo_name);
    }

    public function getPhoto_url()
    {
        return Yii::getAlias('@frontendWeb/data/products/' . $this->photo_name);
    }

    private function upload()
    {
        $dir = Yii::getAlias('@data/products');
        FileHelper::createDirectory($dir, $mode = 0775, $recursive = true);

        $file = UploadedFile::getInstance($this, 'photo');
        $unique = uniqid();

        if ($file instanceof UploadedFile) {
            $name = "{$this->id}_photo_{$unique}.{$file->extension}";
            $path = $dir . DIRECTORY_SEPARATOR . $name;
            $file->saveAs($path, false);

            if ($this->photo_name) {
                @unlink($dir . DIRECTORY_SEPARATOR . $this->photo_name);
            }

            $this->photo_name = $name;
            $this->updateAttributes(['photo_name']);
        }

        if (($_ENV['YII_ENV'] ?? null) == 'dev') {
            $frontendDir = Yii::getAlias("@frontendWebroot/data/products");
            FileHelper::copyDirectory($dir, $frontendDir);
            $backendDir = Yii::getAlias("@backendWebroot/data/products");
            FileHelper::copyDirectory($dir, $backendDir);
        }
    }
}
