<?php
namespace common\components\LiqPay\models;

use InvalidArgumentException;

/**
 * Class Subscribe
 * @package common\components\LiqPay
 *
 * @property string $subscribe
 * @property string $dateStart
 * @property string $periodicity
 */
class Subscribe extends Model
{
    const MONTH = 'month';
    const YEAR = 'year';

    /**
     * Список доступных переодичностей платежа
     *
     * @var array $available_periodicity
     */
    public static $available_periodicity = [
        self::MONTH,
        self::YEAR,
    ];

    /**
     * Регулярный платеж.Возможные значения: '1'
     *
     * @var string $subscribe
     */
    private $subscribe = '1';

    /**
     * Дата первого платежа
     *
     * @var string $date_start
     */
    private $date_start;

    /**
     * Периодичность списания средств
     *
     * @var string $periodicity
     */
    private $periodicity;

    /**
     * Subscribe constructor.
     * @param string|integer $date_start
     * @param string $periodicity
     */
    public function __construct($date_start, $periodicity)
    {
        if (!in_array($periodicity, self::$available_periodicity)) {
            throw new InvalidArgumentException("Periodicity '{$periodicity}' not available for subscribe");
        }

        if (is_int($date_start)) {
            $date_start = date('Y-m-d H:i:s', $date_start);
        } elseif (is_string($date_start)) {
            $date_start = date('Y-m-d H:i:s', strtotime($date_start));
        } else {
            $type = gettype($date_start);
            throw new InvalidArgumentException("Date type '{$type}' not available for subscribe");
        }

        $this->date_start = $date_start;
        $this->periodicity = $periodicity;
    }

    /**
     * @return string
     */
    public function getSubscribe()
    {
        return $this->subscribe;
    }

    /**
     * @return string
     */
    public function getDateStart()
    {
        return $this->date_start;
    }

    /**
     * @return string
     */
    public function getPeriodicity()
    {
        return $this->periodicity;
    }
}
