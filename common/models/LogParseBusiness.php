<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "logParseBusiness".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $message
 * @property integer $isFail
 * @property integer $business_id
 * @property integer $city_id
 * @property City $city
 * @property Business $business
 */
class LogParseBusiness extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logParseBusiness';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'message', 'full_url'], 'string'],
            [['isFail', 'business_id', 'city_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['dateCreate'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'url' => 'Url',
            'message' => 'Message',
            'isFail' => 'Is Fail',
            'dateCreate' => 'Date Create',
        ];
    }
    
    public function behaviors()
    {
        return array(
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => new Expression('NOW()'),
                'attributes' => array(
                    ActiveRecord::EVENT_BEFORE_INSERT => 'dateCreate',
                ),
            ],
        );
    }

    public function getBusiness()
    {
        return $this->hasOne(Business::className(), ['id' => 'business_id']);
    }

    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }
}
