<?php

namespace common\models;

use Yii;
use common\components\ActiveRecordMultiLang;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "work_category".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $dateUpdate
 * @property string $sitemap_en
 * @property string $sitemap_priority
 * @property string $sitemap_changefreq
 * @property string $seo_description
 * @property string $seo_keywords
 * @property string $seo_title
 * @property string $seo_description_resume
 * @property string $seo_keywords_resume
 * @property string $seo_title_resume
 */
class WorkCategory extends ActiveRecordMultiLang
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
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => new Expression('NOW()'),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['dateUpdate'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['dateUpdate'],
                ],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'work_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required','message' => 'Поле не может быть пустым'],
            [['dateUpdate','sitemap_en','sitemap_priority','sitemap_changefreq'], 'safe'],
            [
                [
                    'title', 'seo_description', 'seo_keywords', 'seo_title',
                    'seo_description_resume', 'seo_keywords_resume', 'seo_title_resume'
                ],
                'string'
            ],
            [['url'], 'string', 'max' => 255]
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
            'seo_description'=>'Вакансии: SEO Description',
            'seo_keywords'=>'Вакансии: SEO Ключевые слова',
            'seo_title' => 'Вакансии: Seo Title',
            'seo_description_resume'=>'Резюме: SEO Description',
            'seo_keywords_resume'=>'Резюме: SEO Ключевые слова',
            'seo_title_resume' => 'Резюме: Seo Title',
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
            'seo_description_resume',
            'seo_keywords_resume',
            'seo_title_resume',
        ];
    }
}
