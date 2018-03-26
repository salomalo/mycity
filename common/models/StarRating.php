<?php

namespace common\models;

use yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "star_rating".
 *
 * @property integer $id
 * @property string $object_id
 * @property integer $user_id
 * @property string $date
 * @property integer $rating
 * @property integer $object_type
 */
class StarRating extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'star_rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'rating', 'object_type'], 'integer'],
            [['object_id'], 'string'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object_id' => 'Business ID',
            'user_id' => 'User ID',
            'date' => 'Date',
            'rating' => 'Rating',
            'object_type' => 'Type',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array(
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
                'attributes' => array(
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['date'],
                ),
            ],
        );
    }
}
