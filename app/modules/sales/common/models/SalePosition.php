<?php

namespace modules\sales\common\models;

use modules\profiles\common\models\Profile;
use yz\interfaces\ModelInfoInterface;
use modules\actions\common\models\ActionPosition;
use modules\actions\common\models\ActionPositionProduct;

/**
 * This is the model class for table "yz_sales_positions".
 *
 * @property integer $id
 * @property integer $sale_id
 * @property integer $product_id
 * @property integer $serial_number_id
 * @property integer $custom_serial
 * @property integer $quantity
 * @property integer $bonuses
 * @property integer $bonuses_primary
 * @property integer $cost
 * @property string $validation_method
 * @property integer $category_id
 *
 * @property string $cost_real
 * @property Sale $sale
 * @property Product $product
 * @property Unit $unit
 * @property Category category
 */
class SalePosition extends \yz\db\ActiveRecord implements ModelInfoInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sales_positions}}';
    }

    /**
     * Returns model title, ex.: 'Person', 'Book'
     *
     * @return string
     */
    public static function modelTitle()
    {
        return 'Товарная позиция';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     *
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Товарные позиции';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['product_id', 'required', 'message' => 'Не выбран товар'],
            ['product_id', 'in', 'range' => array_keys(self::getProductIdValues()), 'message' => 'Выбранный товар не доступен'],
            ['bonuses', 'integer'],
            ['bonuses_primary', 'integer'],
            ['quantity', 'integer'],
            ['quantity', 'required'],
            ['category_id', 'integer'],
            ['sale_id', 'required'],
            ['quantity', 'compare', 'compareValue' => 0, 'type' => 'number', 'operator' => '>',
                'message' => 'Количество должо быть больше нуля'],
            ['cost', 'integer'],
        ];
    }

    public static function getProductIdValues()
    {
        return Product::find()->select('name, id')->indexBy('id')->column();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sale_id' => 'ID продажи',
            'product_id' => 'Продукция',
            'quantity' => 'Количество',
            'bonuses' => 'Бонусы',
            'bonuses_primary' => 'Бонусы за 1ед на момент оформления',
            'validation_method' => 'Способ подтверждения',
            'cost' => 'Стоимость',
            'category_id' => 'Категория',
        ];
    }

    public function fields()
    {
        return [
            'product_id',
            'quantity',
            'bonuses',
            'bonuses_primary',
            'product',
            'category_id',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::class, ['id' => 'unit_id'])->via('product');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSale()
    {
        return $this->hasOne(Sale::class, ['id' => 'sale_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id'])->via('product');
    }

    public function getCost_real()
    {
        return empty($this->cost) ? null : str_replace(',', '.', ($this->cost / 100) . '');
    }

    /**
     * Каждая продажа привязана в акции, в которой указывается формула для расчета баллов для категории и продукции
     *
     * @param bool $save
     * @param integer $action_id
     * @return integer
     */
    public function updateBonuses($save = true, $role)
    {
        $bonuses = 0;
        if($role === Profile::ROLE_DESIGNER) {
            $bonuses = ($this->bonuses_primary * (int)$this->quantity)*0.01;
        } else {
            $bonuses = $this->bonuses_primary * (int)$this->quantity;
        }

        if ($save) {
            $this->updateAttributes(['bonuses' => $bonuses]);
        }

        return $bonuses;
    }

}
