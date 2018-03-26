<?php

namespace common\models;

use Yii;
use common\components\ActiveRecordMultiLang;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "post_category".
 *
 * @property integer $id
 * @property string $title
 * @property string $image
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 */
class PostCategory extends ActiveRecordMultiLang
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
        return 'post_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required','message' => 'Поле не может быть пустым'],
            [['dateUpdate','sitemap_en','sitemap_priority','sitemap_changefreq'], 'safe'],
            [['url'], 'string', 'max' => 255],
            [['image'], 'file', 'maxSize' => Yii::$app->params['maxSizeUpload'], 'extensions'=>'jpg, gif, png'],
            [['title', 'seo_description','seo_keywords','seo_title'], 'string']
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
            'seo_description'=>'SEO Description',
            'seo_keywords'=>'SEO Ключевые слова',
            'seo_title' => 'Seo Title',
            'dateUpdate' => 'Дате обновления',
            'sitemap_en' => 'Добавить в sitemap',
            'sitemap_priority' => 'Sitemap приоритет',
            'sitemap_changefreq' => 'Sitemap частота изменения',
            
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

    public static function getAll()
    {
        return ArrayHelper::map(PostCategory::find()->all(), 'id', 'title');
    }
}
