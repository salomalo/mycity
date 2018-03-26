<?php
namespace common\components\LiqPay;

use common\models\Lang;
use ReflectionClass;

final class LiqPayErrors
{
    const AUTH = 'err_auth';
    const CACHE = 'err_cache';
    const USER_NOT_FOUND = 'user_not_found';
    const SMS_SEND = 'err_sms_send';
    const SMS_OTP = 'err_sms_otp';
    const SHOP_BLOCKED = 'shop_blocked';
    const SHOP_NOT_ACTIVE = 'shop_not_active';
    const INVALID_SIGNATURE = 'invalid_signature';
    const ORDER_ID_EMPTY = 'order_id_empty';
    const SHOP_NOT_AGENT = 'err_shop_not_agent';
    const CARD_DEF_NOT_FOUND = 'err_card_def_notfound';
    const NO_CARD_TOKEN = 'err_no_card_token';
    const CARD_LIQPAY_DEF = 'err_card_liqpay_def';
    const CARD_TYPE = 'err_card_type';
    const CARD_COUNTRY = 'err_card_country';
    const LIMIT_AMOUNT = 'err_limit_amount';
    const PAYMENT_AMOUNT_LIMIT = 'err_payment_amount_limit';
    const AMOUNT_LIMIT = 'amount_limit';
    const PAYMENT_SENDER_CARD = 'payment_err_sender_card';
    const PAYMENT_PROCESSING = 'payment_processing';
    const PAYMENT_DISCOUNT = 'err_payment_discount';
    const WALLET = 'err_wallet';
    const GET_VERIFY_CODE = 'err_get_verify_code';
    const VERIFY_CODE = 'err_verify_code';
    const WAIT_INFO = 'wait_info';
    const PATH = 'err_path';
    const PAYMENT_CASH_ACQ = 'err_payment_cash_acq';
    const SPLIT_AMOUNT = 'err_split_amount';
    const CARD_RECEIVER_DEF = 'err_card_receiver_def';
    const PAYMENT_STATUS = 'payment_err_status';
    const PUBLIC_KEY_NOT_FOUND = 'public_key_not_found';
    const PAYMENT_NOT_FOUND = 'payment_not_found';
    const PAYMENT_NOT_SUBSCRIBED = 'payment_not_subscribed';
    const WRONG_AMOUNT_CURRENCY = 'wrong_amount_currency';
    const AMOUNT_HOLD = 'err_amount_hold';
    const ACCESS = 'err_access';
    const ORDER_ID_DUPLICATE = 'order_id_duplicate';
    const BLOCKED = 'err_blocked';
    const EMPTY_ERROR = 'err_empty';
    const EMPTY_PHONE = 'err_empty_phone';
    const MISSING = 'err_missing';
    const WRONG = 'err_wrong';
    const WRONG_CURRENCY = 'err_wrong_currency';
    const PHONE = 'err_phone';
    const CARD = 'err_card';
    const CARD_BIN = 'err_card_bin';
    const TERMINAL_NOT_FOUND = 'err_terminal_notfound';
    const COMMISSION_NOT_FOUND = 'err_commission_notfound';
    const PAYMENT_CREATE = 'err_payment_create';
    const MPI = 'err_mpi';
    const LIMIT = 'limit';
    const CURRENCY_NOT_ALLOWED = 'err_currency_is_not_allowed';
    const LOOK = 'err_look';
    const MODS_EMPTY = 'err_mods_empty';
    const ERR_TYPE = 'payment_err_type';
    const PAYMENT_CURRENCY = 'err_payment_currency';
    const PAYMENT_EXCHANGERATES = 'err_payment_exchangerates';
    const SIGNATURE = 'err_signature';
    const API_ACTION = 'err_api_action';
    const API_CALLBACK = 'err_api_callback';
    const API_IP = 'err_api_ip';
    const CARD_3DS_NOT_AVAILABLE = '5';

    const ERROR_WHILE_PROCESSING = '90';
    const TOKEN_NOT_FROM_THIS_MERCHANT = '101';
    const TOKEN_NOT_ACTIVE = '102';
    const EXCEEDED_PURCHASE_LIMIT = '103';
    const EXCEEDED_TRANSACTION_LIMIT = '104';
    const CARD_NOT_ALLOW = '105';
    const MERCHANT_MAY_NOT_PREVENT_AUTH = '106';
    const ACQUIRE_NOT_ALLOW_3DS = '107';
    const TOKEN_NOT_EXIST = '108';
    const EXCEEDED_TRIES_BY_IP = '109';
    const SESSION_EXPIRED = '110';
    const CARD_BRANCH_IS_BLOCKED = '111';
    const EXCEEDED_CARD_BRANCH_DAILY_LIMIT = '112';
    const P2P_NOT_POSSIBLE_FROM_PB_TO_FOREIGN = '113';
    const EXCEEDED_COMPLETE_LIMIT = '114';
    const INVALID_RECIPIENT_NAME = '115';
    const EXCEEDED_CARD_USE_DAILY_LIMIT = '2903';
    const ORDER_ID_EXIST = '2915';
    const COUNTRY_NOT_ALLOW = '3914';
    const EXPIRED_CARD = '9851';
    const INVALID_CARD = '9852';
    const PAYMENT_DECLINED = '9854';
    const TRANSACTION_NOT_ALLOW_BY_CARD = '9855';
    const TRANSACTION_NOT_ALLOW_BY_CARD_ALIAS = '9857';
    const INSUFFICIENT_FUNDS = '9859';
    const EXPIRED_CARD_OPERATION_LIMIT = '9860';
    const WOULD_BE_EXPIRED_CASH_LIMIT = '9861';
    const EXPIRED_CASH_LIMIT = '9863';
    const INVALID_TRANSACTION_AMOUNT = '9867';
    const OPERATION_NOT_CONFIRMED_BY_BANK = '9868';
    const DESTINATION_NOT_AVAILABLE = '9872';
    const INVALID_PARAMETERS = '9882';
    const MERCHANT_NOT_ALLOW_RECURRENCE_PAYMENTS = '9886';
    const EXCEEDS_WITHDRAWAL_LIMIT = '9961';
    const INVALID_CARD_DETAILS = '9989';
    
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

        $errors = [];
        $lang = Lang::getCurrent()->url;
        $path = __DIR__ . "/messages/$lang";

        if (is_dir($path)) {
            $errors = require("$path/errors.php");
        }

        foreach ($constants as $constant) {
            $labels[$constant] = isset($errors[$constant]) ? $errors[$constant] : $constant;
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
        $errors = [];
        $lang = Lang::getCurrent()->url;
        $path = __DIR__ . "/messages/$lang";

        if (is_dir($path)) {
            $errors = require("$path/errors.php");
        }

        return isset($errors[$val]) ? $errors[$val] : $val;
    }
}
