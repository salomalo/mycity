<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rating".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $pid
 * @property integer $rating
 */
class Rating extends ActiveRecord
{
    const TYPE_ACCOUNT              = 1;
    const TYPE_BUSINESS             = 2;
    const TYPE_POST                 = 3;   
    const TYPE_RESUME               = 4;
    const TYPE_PRODUCT              = 5;   
    const TYPE_PRODUCT_GALLERY      = 6;
    const TYPE_ADS                  = 7;
    const TYPE_ADS_GALLERY          = 8;
    const TYPE_GALLERY              = 9;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'pid', 'rating'], 'required','message' => 'Поле не может быть пустым'],
            [['type', 'pid', 'rating'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'pid' => 'Pid',
            'rating' => 'Rating',
        ];
    }
}
