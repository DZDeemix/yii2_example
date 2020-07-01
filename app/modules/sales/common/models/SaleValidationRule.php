<?php

namespace modules\sales\common\models;

use modules\sales\common\sales\validation\RuleEvaluator;
use Symfony\Component\ExpressionLanguage\SyntaxError;
use Yii;
use yii\base\ErrorException;
use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_sale_validation_rules".
 *
 * @property integer $id
 * @property string $name
 * @property integer $is_enabled
 * @property string $rule
 * @property string $error
 *
 * @property RuleEvaluator $evaluator
 */
class SaleValidationRule extends \yz\db\ActiveRecord implements ModelInfoInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sales_validation_rules}}';
    }

	/**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return 'Правило';
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return 'Правила проверки закупок';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['is_enabled', 'integer'],

            ['name', 'string', 'max' => 128],
            ['name', 'required'],

            ['rule', 'string', 'max' => 255],
            ['rule', 'required'],
            ['rule', 'validateRule'],

            ['error', 'string', 'max' => 255],
        ];
    }

    public function validateRule()
    {
        try {
            (new RuleEvaluator($this))->evaluate(new Sale(), [], []);
        } catch (SyntaxError $e) {
            $this->addError('rule', 'Формула имеет неверный формат: '.$e->getMessage());
        } catch (ErrorException $e) {
            $this->addError('rule', 'При проверки формулы выявлена следующая ошибка: ' . $e->getMessage());
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'is_enabled' => 'Задействовано',
            'rule' => 'Правило',
            'name' => 'Название',
            'error' => 'Сообщение об ошибке',
        ];
    }

    /**
     * @var RuleEvaluator
     */
    private $_evaluator;

    public function getEvaluator()
    {
        if ($this->_evaluator === null) {
            $this->_evaluator = new RuleEvaluator($this);
        }

        return $this->_evaluator;
    }
}
