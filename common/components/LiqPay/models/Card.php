<?php
namespace common\components\LiqPay\models;

/**
 * Class Card
 * @package common\components\LiqPay
 * 
 * @property string $number
 * @property string $expMonth
 * @property string $expYear
 * @property string $cvv
 */
class Card extends Model
{
    /**
     * Номер карты
     * @var string $number
     */
    private $number;

    /**
     * Месяц действия карты
     * @var string $exp_month
     */
    private $exp_month;

    /**
     * Год действия карты
     * @var string $exp_year
     */
    private $exp_year;

    /**
     * CVV/CVV2
     * @var string $cvv
     */
    private $cvv;

    /**
     * Card constructor.
     * @param string $number
     * @param string $exp_month
     * @param string $exp_year
     * @param string $cvv
     */
    public function __construct($number, $exp_month, $exp_year, $cvv)
    {
        $this->number = $number;
        $this->exp_month = $exp_month;
        $this->exp_year = $exp_year;
        $this->cvv = $cvv;
    }

    /**
     * @return string
     */
    public function getExpYear()
    {
        return $this->exp_year;
    }

    /**
     * @return string
     */
    public function getExpMonth()
    {
        return $this->exp_month;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getCvv()
    {
        return $this->cvv;
    }
}
