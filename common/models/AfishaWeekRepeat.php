<?php

namespace common\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "afisha_week_repeat".
 *
 * @property integer $id
 * @property integer $afisha_id
 * @property boolean $mon
 * @property boolean $tue
 * @property boolean $wed
 * @property boolean $thu
 * @property boolean $fri
 * @property boolean $sat
 * @property boolean $sun
 * @property array $arrayOfDays
 * @property string $stringOfDays
 */
class AfishaWeekRepeat extends ActiveRecord
{
    public static $days = [
        'mon' => 'Понедельник',
        'tue' => 'Вторник',
        'wed' => 'Среда',
        'thu' => 'Четверг',
        'fri' => 'Пятница',
        'sat' => 'Суббота',
        'sun' => 'Воскресенье',
    ];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'afisha_week_repeat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['afisha_id'], 'integer'],
            [['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'afisha_id' => 'Afisha ID',
            'mon' => 'Mon',
            'tue' => 'Tue',
            'wed' => 'Wed',
            'thu' => 'Thu',
            'fri' => 'Fri',
            'sat' => 'Sat',
            'sun' => 'Sun',
        ];
    }

    public function getArrayOfDays()
    {
        $array = [];
        $days = array_keys($this::$days);
        foreach ($days as $day) {
            if ($this->{$day}) {
                $array[] = $day;
            }
        }
        return $array;
    }

    public function getStringOfDays()
    {
        $array = [];
        $days = array_keys($this::$days);
        foreach ($days as $day) {
            if ($this->{$day}) {
                $array[] = isset($this::$days[$day]) ? mb_strtolower($this::$days[$day], 'UTF-8') : '';
            }
        }
        return implode(', ', $array);
    }
    
    public static function saveDays(Afisha $afisha)
    {
        if (((int)$afisha->repeat === $afisha::REPEAT_WEEK) and is_array($afisha->repeatDays)) {
            if (!$afisha->afishaWeekRepeat) {
                $afishaWeekRepeat = new AfishaWeekRepeat(['afisha_id' => $afisha->id]);
            } else {
                $afishaWeekRepeat = AfishaWeekRepeat::findOne(['afisha_id' => $afisha->id]);
            }
            foreach (AfishaWeekRepeat::$days as $day => $label) {
                $afishaWeekRepeat->{$day} = (in_array($day, $afisha->repeatDays)) ? true : false;
            }
            $afishaWeekRepeat->save();
        } else {
            AfishaWeekRepeat::deleteAll(['afisha_id' => $afisha->id]);
        }
    }
}
