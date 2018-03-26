<?php

namespace common\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "counter_city".
 *
 * @property integer $id
 * @property integer $counter_id
 * @property integer $city_id
 *
 * @property City $city
 * @property Counter $counter
 */
class CounterCity extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'counter_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['counter_id', 'city_id'], 'required'],
            [['counter_id', 'city_id'], 'integer'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['counter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Counter::className(), 'targetAttribute' => ['counter_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'counter_id' => 'Counter ID',
            'city_id' => 'City ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCounter()
    {
        return $this->hasOne(Counter::className(), ['id' => 'counter_id']);
    }
}
