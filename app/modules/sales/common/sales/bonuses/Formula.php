<?php

namespace modules\sales\common\sales\bonuses;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;


/**
 * Class Formula
 */
class Formula
{
    /**
     * @var ExpressionLanguage
     */
    private static $language;

    /**
     * @var string
     */
    private $formula;

    function __construct($formula)
    {
        $this->formula = $formula;
    }

    /**
     * @return string
     */
    public function getFormula()
    {
        return $this->formula;
    }

    /**
     * @param array $variables
     * @return integer
     */
    public function evaluate($variables = [])
    {
        return max((int)round(self::language()->evaluate($this->formula, $variables)), 0);
    }

    /**
     * @return ExpressionLanguage
     */
    private static function language()
    {
        if (self::$language === null) {
            self::$language = new ExpressionLanguage();
        }
        return self::$language;
    }


}