<?php

namespace frontend\extensions\BusinessFormatTime;

use common\models\BusinessTime;
use yii;
use yii\base\Widget;

/**
 * Class BusinessFormatTime
 * @package frontend\extensions\BusinessFormatTime
 *
 *
 * @property string $workingTime
 */
class BusinessFormatTime extends Widget
{
    const T_CAT = 'widgets';
    const ZERO_TIME = '00:00';

    /** @var int $id */
    public $id;

    /** @var bool $format */
    public $format = true;

    /** @var BusinessTime[] $models */
    public $models;

    /** @var array $times */
    private $times = [];

    /** @var array $weekends */
    private $weekends = [];

    /** @var array $working */
    private $working = [];

    /** @var array $days */
    private $days;
    private $daysE;

    public function init()
    {
        if (!$this->models) {
            $this->models = BusinessTime::find()->where(['idBusiness' => $this->id])->all();
        }
        foreach ($this->models as $model) {
            $start       = substr($model->start, 0, 5);
            $end         = substr($model->end, 0, 5);
            $break_start = substr($model->break_start, 0, 5);
            $break_end   = substr($model->break_end, 0, 5);

            $this->times[$model->weekDay] = [
                'start'         => $start,
                'end'           => $end,
                'break_start'   => $break_start,
                'break_end'     => $break_end,
                'weekend'       => $this->checkTime($start, $end),
                'break'         => !$this->checkTime($break_start, $break_end, false),
            ];
        }
        for ($i = 1; $i <= 7; $i++) {
            if (!isset($this->times[$i]) or ($this->times[$i]['weekend'])) {
                $this->weekends[] = $i;
                $this->times[$i]['weekend']     = true;
                $this->times[$i]['start']       = self::ZERO_TIME;
                $this->times[$i]['end']         = self::ZERO_TIME;
                $this->times[$i]['break_start'] = self::ZERO_TIME;
                $this->times[$i]['break_end']   = self::ZERO_TIME;
                $this->times[$i]['break']       = false;
            } else {
                $this->working[] = $i;
                if (!$this->times[$i]['break']) {
                    $this->times[$i]['break_start'] = self::ZERO_TIME;
                    $this->times[$i]['break_end']   = self::ZERO_TIME;
                }
            }
        }
        $this->days = [
            1 => $this->t('Mon'),
            $this->t('Tue'),
            $this->t('Wed'),
            $this->t('Thu'),
            $this->t('Fri'),
            $this->t('Sat'),
            $this->t('Sun')
        ];
        $this->daysE = [
            1 => 'Mo',
            'Tu',
            'We',
            'Th',
            'Fr',
            'Sa',
            'Su',
        ];
        parent::init();
    }

    public function run()
    {
        if ($this->models) {
            return $this->format ? $this->render('view', ['out' => $this->workingTime]) : $this->workingTime;
        }
        return '';
    }

    /**
     * @param string $start
     * @param string $end
     * @param bool $onlyZero
     * @return bool
     */
    private function checkTime($start, $end, $onlyZero = true)
    {
        return (($start === $end) and (!$onlyZero or ($start === self::ZERO_TIME))) ? true : false;
    }

    /**
     * @param $times array
     * @param $eng bool
     * @return string
     */
    private function formatTime($times, $eng = false)
    {
        $result = [];

        $result[] = $eng ? '' : ':';
        if (!$eng) $result[] = $this->t('from');
        $result[] = $times['start'];
        $result[] = $eng ? '-' : $this->t('to');
        $result[] = $times['end'];

        if ($times['break']) {
            if (!$eng) $result[] = $this->t('break');
            if (!$eng) $result[] = $this->t('from');
            $result[] = $times['break_start'];
            $result[] = $eng ? '-' : $this->t('to');
            $result[] = $times['break_end'];
        }

        return (string)implode(' ', $result);
    }

    /**
     * @param $day int
     * @return string
     */
    private function getDay($day)
    {
        return $this->days[$day];
    }

    /**
     * @return string
     */
    public function getWorkingTime()
    {
        $out = [];
        $out[] = $this->format ? "<br>\n" : '';
        switch (count($this->working)) {
            case 0:
                $out[] = $this->format ? $this->t('null') : '';
                break;
            case 7:
                $out[] = $this->formatTimeToString($this->groupWorkingByTime($this->working));
                $out[] = $this->t('without_weekend');
                break;
            default:
                $out[] = $this->formatTimeToString($this->groupWorkingByTime($this->working));
                $out[] = $this->t('weekends') . ': ';
                $out[] = $this->formatTimeToString($this->groupWorkingByTime($this->weekends), false);
        }

        return (string)implode($out);
    }

    /**
     * Группировка дней для вывода
     * @param $array
     * @return array
     */
    private function groupWorkingByTime($array)
    {
        $result = [];
        $groups = [];
        $previous_day = 0;
        $previous_index = null;
        /**
         * Индекс группирует по времени, группа - по безпрерывности рабочих дней
         * [
         *  index_i => [
         *      days => [
         *          group_i => [
         *              from => day_i, to => day_i(or null)
         *          ],
         *          ...
         *      ],
         *      times => [
         *          'start' => time_start, 'end' => time_end,
         *          'break_start' => break_start, 'break_end' => break_end
         *      ],
         *  ],
         *  ...
         * ]
         */
        foreach ($array as $current_day) {
            $day = $this->times[$current_day];
            $index = $day['start'] . $day['end'] . $day['break_start'] . $day['break_end'];
            if (empty($result[$index])) {
                $result[$index]['times']['start']       = $day['start'];
                $result[$index]['times']['end']         = $day['end'];
                $result[$index]['times']['break_start'] = $day['break_start'];
                $result[$index]['times']['break_end']   = $day['break_end'];
                $result[$index]['times']['break']       = $day['break'];
                $groups[$index] = 0;
                $result[$index]['days'][$groups[$index]]['from'] = $current_day;
            } else {
                if ($current_day === ($previous_day + 1) and ($index === $previous_index)) {
                    $result[$index]['days'][$groups[$index]]['to'] = $current_day;
                } else {
                    $groups[$index]++;
                    $result[$index]['days'][$groups[$index]]['from'] = $current_day;
                }
            }
            $previous_day = $current_day;
            $previous_index = $index;
        }
        return $result;
    }

    /**
     * @param array $gropes
     * @param bool $withTime
     * @return string mixed
     */
    private function formatTimeToString($gropes, $withTime = true)
    {
        //<meta itemprop="openingHours" content="Mo-Sa 11:00-14:30">
        $out = [];
        foreach ($gropes as $interval) {
            $str = [];
            $meta = [];
            $meta[] = "<meta itemprop=\"openingHours\" content=\"";
            foreach ($interval['days'] as $group) {
                if (isset($group['to'])) {
                    $meta[] = $this->daysE[$group['from']] . '-' . $this->daysE[$group['to']];
                    $str[] = $this->getDay($group['from']) . ' - ' . $this->getDay($group['to']);
                } else {
                    $meta[] = $this->daysE[$group['from']];
                    $str[] = $this->getDay($group['from']);
                }
            }
            $meta[] = $withTime ? $this->formatTime($interval['times'], true) : '';
            $meta[] = '">';
            $str[] = $withTime ? $this->formatTime($interval['times']) : '';
            $str[] = ';';
            $str[] = $this->format ? "<br>\n" : ' ';
            if ($withTime) $out[] = implode($meta);
            $out[] = implode($str);
        }

        return (string)implode($out);
    }

    /**
     * @param string $key
     * @return string
     */
    private function t($key)
    {
        return Yii::t(self::T_CAT, $key);
    }
}
