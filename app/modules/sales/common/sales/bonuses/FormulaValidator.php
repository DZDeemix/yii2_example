<?php

namespace modules\sales\common\sales\bonuses;

use Symfony\Component\ExpressionLanguage\SyntaxError;
use yii\base\ErrorException;
use yii\validators\Validator;

/**
 * Class FormulaValidator
 */
class FormulaValidator extends Validator
{
    protected function validateValue($value)
    {
        if (empty($value)) {
            return ['Необходимо указать значение формулы', []];
        }

        if (mb_strlen($value) > 100) {
            return ['Формула слишком длиная', []];
        }

        $formula = new Formula($value);

        try {
            $result = $formula->evaluate(['q' => 10]);
            return null;
        }
        catch (SyntaxError $e) {
            return ['Формула содержит ошибку ' . $e->getMessage(), []];
        }
        catch (ErrorException $e) {
            return ['При проверки формулы выявлена следующая ошибка: ' . $e->getMessage(), []];
        }
    }

}