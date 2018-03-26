<?php

namespace common\models;

use common\behaviors\ImageUpload;
use Yii;

/**
 * This is the model class for table "ads_color".
 *
 * @property integer $id
 * @property string $title
 * @property integer $isShowOnBusiness
 * @property string $image
 * @property string $idAds
 */
class AdsColor extends \yii\db\ActiveRecord
{
    const ENABLED = 1;
    const NOT_ENABLED = 2;

    public static $statusEnabledColor = [
        self::NOT_ENABLED => 'Нет',
        self::ENABLED => 'Да',
    ];

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
        return 'ads_color';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['isShowOnBusiness'], 'integer'],
            [['title', 'isShowOnBusiness'] , 'required'],
            [['title', 'image', 'idAds'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'isShowOnBusiness' => 'В наличии',
            'image' => 'Изображение',
            'idAds' => 'Id Ads',
        ];
    }

    public function getAds(){
        $this->hasOne(Ads::className(), ['idAds' => '_id']);
    }
}
