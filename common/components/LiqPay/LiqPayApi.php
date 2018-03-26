<?php
namespace common\components\LiqPay;

use common\components\LiqPay\models\Card;
use common\components\LiqPay\models\Order;
use yii;
use yii\web\Request;

class LiqPayApi
{
    /** @var string $public_key */
    private $public_key;

    /** @var string $private_key */
    private $private_key;

    /** @var string $language */
    private $language;

    /** @var string $version */
    private $version;

    /** @var LiqPayLib $lib */
    private $lib;

    /** @var string $user_ip */
    private $user_ip;

    /**
     * LiqPayApi constructor.
     *
     * @param string $public_key
     * @param string $private_key
     * @param string $language
     * @param string $version
     */
    public function __construct($public_key, $private_key, $language = 'ru', $version = '3')
    {
        $this->version = $version;
        $this->private_key = $private_key;
        $this->public_key  = $public_key;
        $this->language = $language;

        if (is_a(Yii::$app->request, Request::className())) {
            $this->user_ip = Yii::$app->request->userIP;
        } else {
            $this->user_ip = '128.0.0.1';
        }

        $this->lib = new LiqPayLib($this->public_key, $this->private_key);
    }

    /**
     * Приём платежей на персональной странице Liqpay client->server
     * HTML форму необходимо отправить методом POST на URL с двумя параметрами data и signature
     *
     * Объект заказа
     * @param Order $order
     *
     * @param string $action
     *
     * @param $options array
     * @return string
     */
    public function checkout($action, Order $order, $options = [])
    {
        $required = [
            'version' => $this->version,
            'action' => $action,
            'amount' => $order->amount,
            'currency' => $order->currency,
            'description' => $order->description,
            'order_id' => $order->id,
            'ip' => $this->user_ip,
            'language' => $this->language,
        ];

        $params = $options ? array_merge($required, $options) : $required;

        return $this->lib->cnb_form($params);
    }

    /**
     * Оплата по QR-коду
     * Отсканировать код клиент может в приложениях Privat24 / SENDER
     *
     * Объект заказа
     * @param Order $order
     *
     * @param array $options
     *
     * @return string
     */
    public function payQr(Order $order, $options = [])
    {
        $required = [
            'action' => LiqPayActions::PAY_QR,
            'amount' => $order->amount,
            'currency' => $order->currency,
            'description' => $order->description,
            'order_id' => $order->id,
            'ip' => $this->user_ip,
            'language' => $this->language,
        ];
        $params = $options ? array_merge($required, $options) : $required;

        return $this->request($params);
    }

    /**
     * Списание заблокированной суммы
     *
     * @param $amount string
     * @param $order_id string
     *
     * @return string
     */
    public function holdCompletion($amount, $order_id)
    {
        return $this->request([
            'action' => LiqPayActions::HOLD_COMPLETION,
            'amount' => $amount,
            'order_id' => $order_id,
        ]);
    }

    /**
     * Отмена подписки (регулярного платежа)
     *
     * @param $order_id string
     *
     * @return string
     */
    public function unsubscribe($order_id)
    {
        return $this->request([
            'action' => LiqPayActions::UNSUBSCRIBE,
            'order_id' => $order_id,
        ]);
    }

    /**
     * Проверка статуса платежа
     *
     * @param $order_id string
     *
     * @return string
     */
    public function status($order_id)
    {
        return $this->request([
            'action' => LiqPayActions::STATUS,
            'order_id' => $order_id,
        ]);
    }

    /**
     * Возврат средств Клиенту
     *
     * @param $amount string
     * @param $order_id string
     *
     * @return string
     */
    public function refund($amount, $order_id)
    {
        return $this->request([
            'action' => LiqPayActions::REFUND,
            'amount' => $amount,
            'order_id' => $order_id,
        ]);
    }

    /**
     * Добавление произвольных данных в информационное поле уже созданного платежа.
     *
     * @param $order_id string
     * @param $info string
     *
     * @return string
     */
    public function data($order_id, $info)
    {
        return $this->request([
            'action' => LiqPayActions::DATA,
            'order_id' => $order_id,
            'info' => $info,
        ]);
    }

    /**
     * Получение отчета по платежам в формате CSV или JSON
     *
     * @param $date_from string
     * @param $date_to string
     *
     * @return string
     */
    public function reports($date_from, $date_to)
    {
        return $this->request([
            'action' => LiqPayActions::REPORTS,
            'date_from' => $date_from,
            'date_to' => $date_to,
        ]);
    }

    /**
     *  Завершение платежа с возвратом на страницу магазина
     *
     * @param $token string
     *
     * @return string
     */
    public function verify3DS($token)
    {
        return $this->request([
            'action' => LiqPayActions::VERIFY_3DS,
            'token' => $token,
        ]);
    }

    /**
     * Подтвердить паролем транзакцию
     *
     * @param $token string
     * @param $otp string
     *
     * @return string
     */
    public function verifyOTP($token, $otp)
    {
        return $this->request([
            'action' => LiqPayActions::VERIFY_OTP,
            'token' => $token,
            'otp' => $otp,
        ]);
    }

    /**
     * Выставление счета на E-mail клиента
     *
     * @param Order $order
     * @param string $email
     * @param array $options
     *
     * @return string
     */
    public function invoice(Order $order, $email, $options = [])
    {
        $required = [
            'action' => LiqPayActions::INVOICE,
            'amount' => $order->amount,
            'currency' => $order->currency,
            'description' => $order->description,
            'order_id' => $order->id,
            'email' => $email,
        ];
        $params = $options ? array_merge($required, $options) : $required;

        return $this->request($params);
    }

    /**
     * Отмена выставленного счета
     *
     * @param $order_id string
     *
     * @return string
     */
    public function invoiceCancel($order_id)
    {
        return $this->request([
            'action' => LiqPayActions::INVOICE_CANCEL,
            'order_id' => $order_id,
        ]);
    }

    /**
     * Выполнение запроса к API через SDK
     * @param array $config
     *
     * @return string
     */
    private function request($config)
    {
        $config['version'] = $this->version;

        return $this->lib->api('request', $config);
    }

    /**
     * Покупка в магазине server->server
     * Результатом выполнения запроса является перевод денежных средств с карты покупателя на счет магазина.
     *
     * Телефон плательщика. На этот номер будет отправлен OTP пароль подтверждения платежа (Украина +380, Россия +7)
     * @param string $phone
     *
     * Объект заказа
     * @param Order $order
     *
     * Номер карты плательщика
     * @param Card $card
     *
     * @param array $options
     *
     * @return string
     */
    private function pay($phone, Order $order, Card $card, $options = [])
    {
        $required = [
            'action' => LiqPayActions::PAY,
            'phone' => $phone,
            'amount' => $order->amount,
            'currency' => $order->currency,
            'description' => $order->description,
            'order_id' => $order->id,
            'card' => $card->number,
            'card_expMonth' => $card->expMonth,
            'card_expYear' => $card->expYear,
            'card_cvv' => $card->cvv,
            'ip' => $this->user_ip,
            'language' => $this->language,
        ];
        $params = $options ? array_merge($required, $options) : $required;

        return $this->request($params);
    }

    /**
     * Оплата через приложение Privat24/Sender
     *
     * Телефон плательщика. На этот номер будет отправлен OTP пароль подтверждения платежа (Украина +380, Россия +7)
     * @param string $phone
     *
     * Объект заказа
     * @param Order $order
     *
     * @param array $options
     *
     * @return string
     */
    private function paySender($phone, Order $order, $options = [])
    {
        $required = [
            'action' => LiqPayActions::PAY_SENDER,
            'phone' => $phone,
            'amount' => $order->amount,
            'currency' => $order->currency,
            'description' => $order->description,
            'order_id' => $order->id,
            'ip' => $this->user_ip,
            'language' => $this->language,
        ];
        $params = $options ? array_merge($required, $options) : $required;

        return $this->request($params);
    }

    /**
     * Оплата по токену карты без ввода реквизитов карты.
     *
     * Телефон плательщика. На этот номер будет отправлен OTP пароль подтверждения платежа (Украина +380, Россия +7)
     * @param string $phone
     *
     * Объект заказа
     * @param Order $order
     *
     * Токен карты плательщика
     * @param string $card_token
     *
     * @param array $options
     *
     * @return string
     */
    private function payToken($phone, Order $order, $card_token, $options = [])
    {
        $required = [
            'action' => LiqPayActions::PAY_TOKEN,
            'phone' => $phone,
            'amount' => $order->amount,
            'currency' => $order->currency,
            'description' => $order->description,
            'order_id' => $order->id,
            'card_token' => $card_token,
            'ip' => $this->user_ip,
            'language' => $this->language,
        ];
        $params = $options ? array_merge($required, $options) : $required;

        return $this->request($params);
    }

    /**
     * Оплата наличными
     *
     * Телефон плательщика. На этот номер будет отправлен OTP пароль подтверждения платежа (Украина +380, Россия +7)
     * @param string $phone
     *
     * Объект заказа
     * @param Order $order
     *
     * @param array $options
     *
     * @return string
     */
    private function payCash($phone, Order $order, $options = [])
    {
        $required = [
            'action' => LiqPayActions::PAY_CASH,
            'phone' => $phone,
            'amount' => $order->amount,
            'currency' => $order->currency,
            'description' => $order->description,
            'order_id' => $order->id,
            'ip' => $this->user_ip,
            'language' => $this->language,
        ];
        $params = $options ? array_merge($required, $options) : $required;

        return $this->request($params);
    }

    /**
     * Аккредитив - отложенный платеж с банковской гарантией оплаты после подтверждения.
     * Ваш клиент будет увереннее оплачивать заказ картой.
     *
     * Телефон плательщика. На этот номер будет отправлен OTP пароль подтверждения платежа (Украина +380, Россия +7)
     * @param string $phone
     *
     * Объект заказа
     * @param Order $order
     *
     * Номер карты плательщика
     * @param Card $card
     *
     * @param array $options
     *
     * @return string
     */
    private function payLc($phone, Order $order, Card $card, $options = [])
    {
        $required = [
            'action' => LiqPayActions::PAY_LC,
            'phone' => $phone,
            'amount' => $order->amount,
            'currency' => $order->currency,
            'description' => $order->description,
            'order_id' => $order->id,
            'card' => $card->number,
            'card_expMonth' => $card->expMonth,
            'card_expYear' => $card->expYear,
            'card_cvv' => $card->cvv,
            'ip' => $this->user_ip,
            'language' => $this->language,
        ];
        $params = $options ? array_merge($required, $options) : $required;

        return $this->request($params);
    }

    /**
     * Подтверждение платежа от имени плательщика
     *
     * Уникальный ID покупки в Вашем магазине
     * @param $order_id string
     *
     * Флаг подтверждения запроса. Возможные значения: yes, no
     * @param $confirm boolean
     *
     * @return string
     */
    private function payLcConfirm($order_id, $confirm)
    {
        return $this->request([
            'action' => LiqPayActions::PAY_LC_CONFIRM,
            'order_id' => $order_id,
            'confirm' => $confirm ? 'yes' : 'no',
        ]);
    }

    /**
     * Блокировка средств на карте клиента
     *
     * @param $phone string
     * @param Order $order
     * @param Card $card
     * @param array $options
     *
     * @return string
     */
    private function hold($phone, Order $order, Card $card, $options = [])
    {
        $required = [
            'action' => LiqPayActions::HOLD,
            'phone' => $phone,
            'amount' => $order->amount,
            'currency' => $order->currency,
            'description' => $order->description,
            'order_id' => $order->id,
            'card' => $card->number,
            'card_expMonth' => $card->expMonth,
            'card_expYear' => $card->expYear,
            'card_cvv' => $card->cvv,
            'ip' => $this->user_ip,
            'language' => $this->language,
        ];
        $params = $options ? array_merge($required, $options) : $required;

        return $this->request($params);
    }
    /**
     * Подписка в магазине (регулярные платежи)
     *
     * @param $phone string
     * @param Order $order
     * @param Card $card
     * @param array $options
     *
     * @return string
     */

    private function subscribe($phone, Order $order, Card $card, $options = [])
    {
        $required = [
            'action' => LiqPayActions::SUBSCRIBE,
            'phone' => $phone,
            'amount' => $order->amount,
            'currency' => $order->currency,
            'description' => $order->description,
            'order_id' => $order->id,
            'card' => $card->number,
            'card_expMonth' => $card->expMonth,
            'card_expYear' => $card->expYear,
            'card_cvv' => $card->cvv,
            'ip' => $this->user_ip,
            'language' => $this->language,
        ];
        $params = $options ? array_merge($required, $options) : $required;

        return $this->request($params);
    }

    /**
     * Предавторизация карты
     *
     * @param Order $order
     * @param Card $card
     * @param array $options
     *
     * @return string
     */
    private function auth(Order $order, Card $card, $options = [])
    {
        $required = [
            'action' => LiqPayActions::AUTH,
            'amount' => $order->amount,
            'currency' => $order->currency,
            'description' => $order->description,
            'order_id' => $order->id,
            'card' => $card->number,
            'card_expMonth' => $card->expMonth,
            'card_expYear' => $card->expYear,
            'card_cvv' => $card->cvv,
            'ip' => $this->user_ip,
            'language' => $this->language,
        ];
        $params = $options ? array_merge($required, $options) : $required;

        return $this->request($params);
    }

    /**
     * Проверка поддержки 3D-Secure по карте
     *
     * @param Order $order
     * @param Card $card
     * @param array $options
     *
     * @return string
     */
    private function MPI(Order $order, Card $card, $options = [])
    {
        $required = [
            'action' => LiqPayActions::MPI,
            'amount' => $order->amount,
            'currency' => $order->currency,
            'description' => $order->description,
            'order_id' => $order->id,
            'card' => $card->number,
            'card_expMonth' => $card->expMonth,
            'card_expYear' => $card->expYear,
            'card_cvv' => $card->cvv,
            'ip' => $this->user_ip,
            'language' => $this->language,
        ];
        $params = $options ? array_merge($required, $options) : $required;

        return $this->request($params);
    }
}
