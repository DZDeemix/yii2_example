<?php

namespace common\components\helpers;

class StringHelper extends \yii\helpers\StringHelper
{
    /**
	 * Возвращает склонение для числительных
	 * @param integer $number - число
	 * @param array $words - массив с 3-я вариантами склонений (пример: ['раз', 'раза', 'раз'])
	 * @return string
	 */
	public static function numWord($number, $words) {
        $number = abs($number);

		$cases = array (2, 0, 1, 1, 1, 2);

		return $words[ ($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)] ];
	}

    /**
     * Replaces breaks (\n, \t, \r, \f) in target string
     * @param string $string Target string
     * @param string $replace Replacement string (default " ")
     * @return string
     */
	public static function replaceBreaks(string $string = "", $replace = " ")
    {
        $replace = ["\n" => $replace, "\t" => $replace, "\r" => $replace, "\f" => $replace];

        return strtr($string, $replace);
    }
}