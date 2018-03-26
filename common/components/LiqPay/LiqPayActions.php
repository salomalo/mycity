<?php
namespace common\components\LiqPay;

use common\models\Lang;
use ReflectionClass;

final class LiqPayActions
{
    const PAY = 'pay';
    const PAY_DONATE = 'paydonate';
    const PAY_QR = 'payqr';
    const PAY_SENDER = 'paysender';
    const PAY_TOKEN = 'paytoken';
    const PAY_CASH = 'paycash';
    const PAY_TRACK = 'paytrack';
    const PAY_LC = 'paylc';
    const PAY_LC_CONFIRM = 'paylc_confirm';
    const HOLD = 'hold';
    const HOLD_COMPLETION = 'hold_completion';
    const SUBSCRIBE = 'subscribe';
    const UNSUBSCRIBE = 'unsubscribe';
    const STATUS = 'status';
    const REFUND = 'refund';
    const AUTH = 'auth';
    const DATA = 'data';
    const MPI = 'mpi';
    const REPORTS = 'reports';
    const VERIFY_3DS = '3ds_verify';
    const VERIFY_OTP = 'otp_verify';
    const INVOICE = 'invoice_send';
    const INVOICE_CANCEL = 'invoice_cancel';

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

        $actions = [];
        $lang = Lang::getCurrent()->url;
        $path = __DIR__ . "/messages/$lang";

        if (is_dir($path)) {
            $actions = require("$path/actions.php");
        }

        foreach ($constants as $constant) {
            $labels[$constant] = isset($actions[$constant]) ? $actions[$constant] : $constant;
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
        $actions = [];
        $lang = Lang::getCurrent()->url;
        $path = __DIR__ . "/messages/$lang";

        if (is_dir($path)) {
            $actions = require("$path/actions.php");
        }

        return isset($actions[$val]) ? $actions[$val] : $val;
    }
}
