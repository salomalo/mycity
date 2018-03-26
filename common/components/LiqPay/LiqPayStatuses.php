<?php
namespace common\components\LiqPay;

use common\models\Lang;
use ReflectionClass;
use yii;

final class LiqPayStatuses
{
    const REQUEST = 'request';

    const SUCCESS = 'success';
    const FAIL = 'failure';
    const ERROR = 'error';
    const SUBSCRIBED = 'subscribed';
    const UNSUBSCRIBED = 'unsubscribed';
    const REVERSED = 'reversed';
    const SANDBOX = 'sandbox';

    const VERIFY_OTP = 'otp_verify';
    const VERIFY_3DS = '3ds_verify';
    const VERIFY_CVV = 'cvv_verify';
    const VERIFY_SENDER = 'sender_verify';
    const VERIFY_RECEIVER = 'receiver_verify';
    const VERIFY_PHONE = 'phone_verify';
    const VERIFY_IVR = 'ivr_verify';
    const VERIFY_PIN = 'pin_verify';
    const VERIFY_CAPTCHA = 'captcha_verify';
    const VERIFY_PASSWORD = 'password_verify';
    const VERIFY_SENDER_APP = 'senderapp_verify';

    const PROCESSING = 'processing';
    const PREPARED = 'prepared';
    const WAIT_BITCOIN = 'wait_bitcoin';
    const WAIT_SECURE = 'wait_secure';
    const WAIT_ACCEPT = 'wait_accept';
    const WAIT_LC = 'wait_lc';
    const WAIT_HOLD = 'hold_wait';
    const WAIT_CASH = 'cash_wait';
    const WAIT_QR = 'wait_qr';
    const WAIT_SENDER = 'wait_sender';
    const WAIT_CARD = 'wait_card';
    const WAIT_COMPENSATION = 'wait_compensation';
    const WAIT_INVOICE = 'invoice_wait';
    const WAIT_RESERVE = 'wait_reserve';

    private function __construct()
    {
    }

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

        $statuses = [];
        $lang = Lang::getCurrent()->url;
        $path = __DIR__ . "/messages/$lang";

        if (is_dir($path)) {
            $statuses = require("$path/statuses.php");
        }

        foreach ($constants as $constant) {
            $labels[$constant] = isset($statuses[$constant]) ? $statuses[$constant] : $constant;
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
        $statuses = [];
        $lang = Lang::getCurrent()->url;
        $path = __DIR__ . "/messages/$lang";

        if (is_dir($path)) {
            $statuses = require("$path/statuses.php");
        }

        return isset($statuses[$val]) ? $statuses[$val] : $val;
    }
}
