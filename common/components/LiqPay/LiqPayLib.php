<?php
namespace common\components\LiqPay;

use yii;
use InvalidArgumentException;

/**
 * Class LiqPayLib - php-библиотека для использования API LiqPay
 * @package common\components\LiqPay
 */
class LiqPayLib
{
    private $path = '@common/components/LiqPay';
    private $_api_url = 'https://www.liqpay.com/api/';
    private $_checkout_url = 'https://www.liqpay.com/api/3/checkout';

    protected $_supportedCurrencies = [
        LiqPayCurrency::USD,
        LiqPayCurrency::EUR,
        LiqPayCurrency::RUB,
        LiqPayCurrency::UAH,
        LiqPayCurrency::BYN,
        LiqPayCurrency::KZT,
    ];

    private $_public_key;
    private $_private_key;

    /**
     * @param string $public_key
     * @param string $private_key
     * 
     * @throws InvalidArgumentException
     */
    public function __construct($public_key, $private_key)
    {
        if (empty($public_key)) {
            throw new InvalidArgumentException('public_key is empty');
        }

        if (empty($private_key)) {
            throw new InvalidArgumentException('private_key is empty');
        }

        $this->_public_key = $public_key;
        $this->_private_key = $private_key;
    }

    /**
     * Call API
     *
     * @param $path
     * @param array $params
     * 
     * @return string
     * 
     * @throws InvalidArgumentException
     */
    public function api($path, $params = [])
    {
        if (!isset($params['version'])) {
            throw new InvalidArgumentException('version is null');
        }
        $url = "{$this->_api_url}{$path}";
        $public_key = $this->_public_key;
        $private_key = $this->_private_key;
        $data = base64_encode(json_encode(array_merge(compact('public_key'), $params)));
        $signature = base64_encode(sha1("{$private_key}{$data}{$private_key}", 1));
        $postfields = http_build_query(['data' => $data, 'signature' => $signature]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $server_output = curl_exec($ch);
        curl_close($ch);

        return json_decode($server_output);
    }

    /**
     * Создание кнопки оплаты
     * 
     * @param array $params
     * 
     * @return string
     * 
     * @throws InvalidArgumentException
     */
    public function cnb_form($params)
    {
        $view = empty($params['view']) ? 'cnb_form' : $params['view'];

        $params = $this->cnb_params($params);

        return Yii::$app->controller->renderPartial("{$this->path}/views/{$view}", [
            'checkout_url' => $this->_checkout_url,
            'data' => base64_encode(json_encode($params)),
            'signature' => $this->cnb_signature($params),
            'language' => empty($params['language']) ? 'ru' : $params['language'],
        ]);
    }

    /**
     * Создание сигнатуры
     * 
     * @param array $params
     * 
     * @return string
     */
    public function cnb_signature($params)
    {
        $json = base64_encode(json_encode($this->cnb_params($params)));
        $signature = base64_encode(sha1("{$this->_private_key}{$json}{$this->_private_key}", 1));
        
        return $signature;
    }

    /**
     * Проверка параметров
     * 
     * @param array $params
     * 
     * @return array $params
     */
    private function cnb_params($params)
    {
        if (!is_array($params)) {
            throw new InvalidArgumentException('params is not array');
        }
        foreach (['version', 'amount', 'currency', 'description'] as $item) {
            if (!isset($params[$item])) {
                throw new InvalidArgumentException("$item is null");
            }
        }
        if (!in_array($params['currency'], $this->_supportedCurrencies)) {
            throw new InvalidArgumentException('currency is not supported');
        }

        $params['public_key'] = $this->_public_key;

        return $params;
    }
}
