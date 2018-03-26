<?php

namespace common\models;

use common\behaviors\ImageUpload;
use common\behaviors\Slug;
use common\behaviors\WallPost;
use common\models\traits\GetCityTrait;
use yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use common\components\ActiveRecordMultiLang;
use common\models\traits\GetUserTrait;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property integer $idUser
 * @property integer $idCategory
 * @property integer $idCity
 * @property integer $status
 * @property double $lat
 * @property double $lon
 * @property string $title
 * @property string $shortText
 * @property string $fullText
 * @property string $address
 * @property string $dateCreate
 * @property string $image
 * @property string|array $video
 * @property string $url
 * @property string $seo_description
 * @property string $seo_keywords
 * @property string $seo_title
 * @property string $dateUpdate
 * @property string $sitemap_en
 * @property string $sitemap_priority
 * @property string $sitemap_changefreq
 * @property string $videos
 * @property string $tags
 * @property boolean $allCity
 * @property boolean $onlyMain
 * @property array $route
 * @property User $user
 * @property City $city
 * @property PostCategory $category
 * @property CountViews $countView
 * @property Comment $comments
 * @property string $categoryTitle
 * @property integer $business_id
 * @property Business $business
 * @property Comment[] $comment
 */
class Post extends ActiveRecordMultiLang
{
    use GetUserTrait; //public function getUser()
    use GetCityTrait; //public function getCity()

    const TYPE_UNPUBLISHED = 1;
    const TYPE_PUBLISHED   = 2;
    
    public static $types = [
        self::TYPE_UNPUBLISHED  => 'Черновик',
        self::TYPE_PUBLISHED    => 'Опубликовано',
    ];

    /**
     * Поле используется при запросе в бд для сохранения кол-ва комментариев
     * @var integer $comment_count
     */
    public $comment_count;
    
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'dateCreate',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'dateUpdate'
                ],
            ],
            'slug' => [
                'class' => Slug::className(),
                'in_attribute' => 'title',
                'out_attribute' => 'url',
                'translit' => true,
                'max_len' => 200,
            ],
            'ImageUpload' => [
                'class' => ImageUpload::className(),
                'attribute' => ['image'],
                'id' => 'id',
                'pid' => 'pid',
            ],
            'WallPost' => WallPost::className(),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idUser', 'title', 'shortText', 'fullText', 'status'], 'required', 'message' => 'Поле не может быть пустым'],
            [['idUser', 'idCategory', 'idCity', 'status', 'business_id'], 'integer'],
            [['title', 'shortText', 'fullText', 'seo_description','seo_keywords','seo_title'], 'string'],
            [['lat', 'lon'], 'number'],
            [['dateCreate','url', 'video','dateUpdate','sitemap_en','sitemap_priority','sitemap_changefreq', 'tags'], 'safe'],
            [['image'], 'file', 'maxSize' => Yii::$app->params['maxSizeUpload'], 'extensions'=>'jpg, gif, png'],
            [['address'], 'string', 'max' => 255],
            [['allCity', 'onlyMain'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idUser' => 'Автор',
            'idCategory' => 'Категория',
            'title' => 'Заголовок',
            'shortText' => 'Краткое описание',
            'fullText' => 'Полное описание',
            'address' => 'Address',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'dateCreate' => 'Date Create',
            'image' => 'Фото',
            'idCity' => 'Город',
            'video' => 'Видео',
            'seo_description'=>'SEO Description',
            'seo_keywords'=>'SEO Ключевые слова',
            'seo_title' => 'Seo Title',
            'dateUpdate' => 'Дате обновления',
            'sitemap_en' => 'Добавить в sitemap',
            'sitemap_priority' => 'Sitemap приоритет',
            'sitemap_changefreq' => 'Sitemap частота изменения',
            'allCity' => 'Во всех городах',
            'onlyMain' => 'Только на главной',
            'status' => 'Статус',
            'tags' => 'Теги',
            'business_id' => 'Предприятие'
        ];
    }

    public function getBusiness()
    {
        return $this->hasOne(Business::className(), ['id' => 'business_id']);
    }
    
    public function translateAttributes()
    {
        return [
            'title',
            'shortText',
            'fullText',
            'seo_description',
            'seo_keywords',
            'seo_title',
            'tags'
        ];
    }
    
    public function afterFind()
    {
        $this->video = explode(',', $this->video);
    }
    
    public function getVideos($video, $sep = ', ')
    {
        return implode($sep, $video);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(PostCategory::className(), ['id' => 'idCategory']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getCountView()
    {
        return $this->hasOne(CountViews::className(), ['pid' => 'id'])->andOnCondition(['count_views.type' => File::TYPE_POST]);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasMany(Comment::className(), ['pid' => 'id'])->andOnCondition(['comment.type' => File::TYPE_POST]);
    }
    
    public function getComments($id)
    {
        $count = Comment::find()->where(['type' => File::TYPE_POST, 'pid' => $id])->asArray()->count();
        return ($count)? $count : 0;
    }

    /**
     * @param $id
     * @return null|Post
     */
    public static function findModel($id)
    {
        return self::findOne(['id' => $id]);
    }
    
//    public function getGallery()
//    {
//        return $this->hasMany(Gallery::className(), ['pid'=>'id']);
//    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->tags = (is_array($this->tags))? implode(', ', $this->tags) : '';
            if ($this->video) {
                $this->video = implode(',', array_filter($this->video));
            }
            if ($this->idCity) {
                $this->allCity = false;
            }
            return true;
        } else {
            return false;
        }
    }

    public function getRoute()
    {
        if (empty(Yii::$app->request->city) and $this->idCity and $this->city) {
            return ['post/view','alias' => "{$this->id}-{$this->url}", 'subdomain' => $this->city->subdomain];
        }
        return ['post/view','alias' => $this->id . '-' . $this->url];
    }

    public function getFrontendUrl()
    {
        return '/post/' . $this->id;
    }

    public function getSubDomain()
    {
        return !empty($this->city) ? ($this->city->subdomain . '.') : null;
    }

    public function getCategoryTitle()
    {
        return empty($this->category) ? null : $this->category->title;
    }

    public function getSearchShortView()
    {
        return '/post/_post_short';
    }
}
