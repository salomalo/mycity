<?php
namespace common\components\LiqPay\models;

/**
 * Class Order
 * @package common\components\LiqPay
 * 
 * @property $amount string
 * @property $currency string
 * @property $description string
 * @property $id string
 */
class Order extends Model
{
    /** 
     * Сумма платежа
     * @var string $amount 
     */
    private $amount;
    
    /** 
     * Валюта платежа
     * @var string $currency
     */
    private $currency;
    
    /**
     * Назначение платежа
     * @var string $description
     */
    private $description;
    
    /**
     * Уникальный ID покупки в Вашем магазине
     * @var string $id
     */
    private $id;

    /**
     * Order constructor.
     * @param string $amount
     * @param string $currency
     * @param string $description
     * @param string $id
     */
    public function __construct($amount, $currency, $description, $id)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->description = $description;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
