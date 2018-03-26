<?php
namespace common\components\LiqPay;

use common\models\Lang;
use ReflectionClass;

final class LiqPayCurrency
{
    const USD = 'USD';
    const EUR = 'EUR';
    const RUB = 'RUB';
    const UAH = 'UAH';
    const BYN = 'BYN';
    const KZT = 'KZT';

    private function __construct(){}

    /**
     * Массив констант
     *
     * @return array
     */
    public static function getConstants()
    {
        $reflect = new ReflectionClass(self::class);
        return $reflect->getConstants();
    }

    /**
     * Возвращает массив labels констант класса
     *
     * @return array
     */
    public static function getConstantsLabels()
    {
        $constants = self::getConstants();
        $labels = [];

        $currency = [];
        $lang = Lang::getCurrent()->url;
        $path = __DIR__ . "/messages/$lang";

        if (is_dir($path)) {
            $currency = require("$path/currency.php");
        }

        foreach ($constants as $constant) {
            $labels[$constant] = isset($currency[$constant]) ? $currency[$constant] : $constant;
        }

        return $labels;
    }

    /**
     * Возвращает label константы
     *
     * @param string $val
     *
     * @return string
     */
    public static function getConstantLabel($val)
    {
        $currency = [];
        $lang = Lang::getCurrent()->url;
        $path = __DIR__ . "/messages/$lang";

        if (is_dir($path)) {
            $currency = require("$path/currency.php");
        }

        return isset($currency[$val]) ? $currency[$val] : $val;
    }
}
