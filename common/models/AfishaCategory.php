<?php

namespace common\models;

use Yii;
use common\components\ActiveRecordMultiLang;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "afisha_category".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $isFilm
 * @property string $title
 * @property string $image
 * @property string $url
 * @property string $seo_keywords
 * @property string $seo_title
 * @property string $seo_description
 * @property mixed $sitemap_en
 * @property mixed $sitemap_priority
 * @property mixed $sitemap_changefreq
 * @property mixed $dateUpdate
 * @property integer $order
 */
class AfishaCategory extends ActiveRecordMultiLang
{
    public function behaviors()
    {
        return [
            'slug' => [
                'class' => 'common\behaviors\Slug',
                'in_attribute' => 'title',
                'out_attribute' => 'url',
                'translit' => true,
                'mongo' => false
            ]
        ];
    }  
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'afisha_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required','message' => 'Поле не может быть пустым'],
            [['pid', 'isFilm', 'order'], 'integer'],
            [['dateUpdate','sitemap_en','sitemap_priority','sitemap_changefreq'], 'safe'],
            [['title', 'seo_description','seo_keywords','seo_title'], 'string'],
            [['url'], 'string', 'max' => 255],
            [['image'], 'file', 'maxSize' => Yii::$app->params['maxSizeUpload'], 'extensions'=>'jpg, gif, png'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'PiD',
            'title' => 'Title',
            'image' => 'Image',
            'isFilm' => 'IsFilm',
            'seo_description'=>'SEO Description',
            'seo_keywords'=>'SEO Ключевые слова',
            'url' => 'Url',
            'seo_title' => 'Seo Title',
            'dateUpdate' => 'Дате обновления',
            'sitemap_en' => 'Добавить в sitemap',
            'sitemap_priority' => 'Sitemap приоритет',
            'sitemap_changefreq' => 'Sitemap частота изменения',
            'order' => 'Порядок сортировки'
        ];
    }
    
    public function translateAttributes()
    {
        return [
            'title',
            'seo_description',
            'seo_keywords',
            'seo_title',
        ];
    }
    
    public function getParent()
    {
        return $this->hasOne(AfishaCategory::className(), ['id'=>'pid']);
    }
    
    public function getChildren()
    {
        return $this->hasMany(AfishaCategory::className(), ['pid'=>'id'])->asArray();
    }
    
    public function beforeSave($insert) {
       if (parent::beforeSave($insert)) {
            
            $this->dateUpdate = date('Y-m-d');
           
            return true;
        } else {
            return false;
        }
    }
    
    public function afterSave($insert, $changedAttributes = null) {
         
        $file = File::find()->where(['name' => $this->image])->one();
        if($file){
            $file->pid = $this->id;
            $file->update();
        }
          
        parent::afterSave($insert, $changedAttributes);
     }

    public static function getAll($where = null)
    {
        if ($where)
            $return = ArrayHelper::map(self::find()->where($where)->select(['id', 'title'])->all(),'id','title');
        else
            $return = ArrayHelper::map(self::find()->select(['id', 'title'])->all(),'id','title');
        return $return;
    }
}
