<?php
namespace common\components\LiqPay;

use stdClass;
use yii;
use yii\base\Component;
use yii\helpers\Json;
use yii\helpers\Url;
use common\components\LiqPay\models\Order;
use common\components\LiqPay\models\Subscribe;
use common\models\Lang;
use common\models\LiqpayPayment;

/**
 * Class LiqPay - компонент, параметры устанавливаются в конфиге main.php
 * @package common\components\LiqPay
 */
class LiqPay extends Component
{
    /**
     * Объект для вызова методов API
     * @var LiqPayApi $liqPay
     */
    private $liqPay;

    /**
     * Публичный ключ - идентификатор магазина
     * @var string $public_key
     */
    public $public_key;

    /**
     * Секретный ключ
     * @var string $private_key
     */
    public $private_key;

    /**
     * Тестовый режим, для включения установить '1'
     * @var string $sandbox
     */
    public $sandbox;

    /**
     * URL API для уведомлений об изменении статуса платежа (callback)
     * @var string $server_url
     */
    public $server_url;

    public function init()
    {
        $this->server_url = Url::to($this->server_url, true);
        $lang = Lang::getCurrent();
        $this->liqPay = new LiqPayApi($this->public_key, $this->private_key, $lang->url);
    }

    /**
     * Выполнить платеж
     *
     * @param string|array $result_url
     * @param Order $order
     * @param array $options
     * @return string
     */
    public function checkoutPay($result_url, Order $order, $options = [])
    {
        $this->addDefaultOptions($options);
        $this->savePayment($order, LiqPayActions::PAY);
        $options['result_url'] = $result_url;

        return $this->liqPay->checkout(LiqPayActions::PAY, $order, $options);
    }

    /**
     * Блокировка средств на счету отправителя
     *
     * @param string|array $result_url
     * @param Order $order
     * @param array $options
     * @return string
     */
    public function checkoutHold($result_url, Order $order, $options = [])
    {
        $this->addDefaultOptions($options);
        $this->savePayment($order, LiqPayActions::HOLD);
        $options['result_url'] = Url::to($result_url, true);

        return $this->liqPay->checkout(LiqPayActions::HOLD, $order, $options);
    }

    /**
     * Выполнить регулярный платеж
     *
     * @param string|array $result_url
     * @param Order $order
     * @param Subscribe $subscribe
     * @param array $options
     * @return string
     */
    public function checkoutSubscribe($result_url, Order $order, Subscribe $subscribe, $options = [])
    {
        $this->addDefaultOptions($options);
        $this->savePayment($order, LiqPayActions::SUBSCRIBE);
        $options['result_url'] = Url::to($result_url, true);

        $options['subscribe'] = $subscribe->subscribe;
        $options['subscribe_date_start'] = $subscribe->dateStart;
        $options['subscribe_periodicity'] = $subscribe->periodicity;

        return $this->liqPay->checkout(LiqPayActions::SUBSCRIBE, $order, $options);
    }

    /**
     * Выполнить пожертвование
     *
     * @param string|array $result_url
     * @param Order $order
     * @param array $options
     * @return string
     */
    public function checkoutDonate($result_url, Order $order, $options = [])
    {
        $this->addDefaultOptions($options);
        $this->savePayment($order, LiqPayActions::PAY_DONATE);
        $options['result_url'] = Url::to($result_url, true);

        return $this->liqPay->checkout(LiqPayActions::PAY_DONATE, $order, $options);
    }

    /**
     * Проверка статуса оплаты
     *
     * @param string $order_id
     *
     * @return stdClass
     */
    public function getStatus($order_id)
    {
        return $this->liqPay->status($order_id);
    }

    /**
     * Добавление постоянных параметров
     * 
     * @param $options array
     */
    private function addDefaultOptions(&$options)
    {
        if ($this->sandbox) {
            $options['sandbox'] = $this->sandbox;
        }

//        if (empty($options['server_url'])) {
//            $options['server_url'] = $this->server_url;
//        }
    }

    /**
     * Сохранение обзаписи о запросе в базе
     *
     * @param Order $order
     * @param string $action
     * @internal param string $order_id
     */
    private function savePayment(Order $order, $action)
    {
        $payment = new LiqpayPayment([
            'order_id' => $order->id,
            'status' => LiqPayStatuses::REQUEST,
            'action' => $action,
            'amount' => $order->amount,
            'currency' => $order->currency,
        ]);
        $payment->save();
    }

    /**
     * Проврка сигнатуры
     *
     * @param string $data
     * @param string $signature
     *
     * @return bool
     */
    public function checkCallbackSignature($data, $signature)
    {
        $sign = base64_encode(sha1("{$this->private_key}{$data}{$this->private_key}", 1));
        
        return ($signature === $sign);
    }
}
