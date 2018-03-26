<?php

namespace common\models;

use common\models\traits\GetCityTrait;
use Yii;
use common\components\ActiveRecordMultiLang;

/**
 * This is the model class for table "city_detail".
 *
 * @property integer $id
 * @property string $title
 * @property string $about
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property string $google_analytic
 */
class CityDetail extends ActiveRecordMultiLang
{
    use GetCityTrait; //public function getCity()

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city_detail';
    }
    
    public static function primaryKey()
    {
            return ['id'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['title', 'about', 'seo_title', 'seo_description', 'seo_keywords','google_analytic'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'about' => 'О городе',
            'seo_title' => 'Seo Title',
            'seo_description' => 'Seo Description',
            'seo_keywords' => 'Seo Keywords',
            'google_analytic' => 'Google Analytic Code',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function translateAttributes()
    {
        return [
            'title',
            'about',
            'seo_title',
            'seo_description',
            'seo_keywords',
        ];
    }
}
