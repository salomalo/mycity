<?php

namespace common\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "business_time".
 *
 * @property integer $id
 * @property integer $idBusiness
 * @property integer $weekDay
 * @property string $start
 * @property string $end
 * @property string $break_start
 * @property string $break_end
 */
class BusinessTime extends ActiveRecord
{
    const DAY_MO   = 1;
    const DAY_TU   = 2;
    const DAY_WE   = 3;
    const DAY_TH   = 4;
    const DAY_FR   = 5;
    const DAY_ST   = 6;
    const DAY_SU   = 7;

    static $days_week = [
        self::DAY_MO => 'Понедельник', 
        self::DAY_TU => 'Вторник', 
        self::DAY_WE => 'Среда', 
        self::DAY_TH => 'Черверг', 
        self::DAY_FR => 'Пятница', 
        self::DAY_ST => 'Суббота', 
        self::DAY_SU => 'Воскресенье', 
    ];

    static $days_work = [
        self::DAY_MO => 'пн.', 
        self::DAY_TU => 'вт.', 
        self::DAY_WE => 'ср.', 
        self::DAY_TH => 'чт.', 
        self::DAY_FR => 'пн.', 
        self::DAY_ST => 'сб.', 
        self::DAY_SU => 'вс.', 
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business_time';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idBusiness', 'weekDay'], 'required','message' => 'Поле не может быть пустым'],
            [['idBusiness', 'weekDay'], 'integer'],
            [['start', 'end', 'break_start', 'break_end'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idBusiness' => 'Id Business',
            'weekDay' => 'Week Day',
            'start' => 'Start',
            'end' => 'End',
            'break_start' => 'Start break',
            'break_end' => 'End break',
        ];
    }
}
