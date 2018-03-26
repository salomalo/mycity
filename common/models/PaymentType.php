<?php

namespace common\models;

use common\behaviors\ImageUpload;
use yii;
use common\components\ActiveRecordMultiLang;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "payment_type".
 *
 * @property integer $id
 * @property string $title
 * @property string $image
 *
 * @property UserPaymentType[] $userPaymentTypes
 */
class PaymentType extends ActiveRecordMultiLang
{
    public function behaviors()
    {
        return [
            'ImageUpload' => [
                'class' => ImageUpload::className(),
                'attribute' => ['image'],
                'id' => 'id',
                'pid' => 'pid',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string'],
            [['image'], 'string', 'max' => 255],
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
            'image' => 'Image',
        ];
    }
    
    public function translateAttributes()
    {
        return ['title'];
    }

    public static function getAll()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'title');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPaymentTypes()
    {
        return $this->hasMany(UserPaymentType::className(), ['payment_type_id' => 'id']);
    }
}
