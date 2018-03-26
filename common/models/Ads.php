<?php

namespace common\models;

use common\behaviors\ImageUpload;
use common\behaviors\Slug;
use common\components\ActiveRecordMongoMultiLang;
use common\models\queries\AdsQuery;
use common\models\traits\GetBusinessTrait;
use yii;
use common\models\traits\GetUserTrait;
use common\models\traits\GetCityTrait;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class Product
 * @package common\models
 * @property string $_id
 * @property string $title
 * @property string $model
 * @property string $description
 * @property string $dateCreate
 * @property integer $idCategory
 * @property integer $idCompany
 * @property integer $idUser
 * @property integer $idCity
 * @property integer $idBusiness
 * @property integer $idYandex
 * @property integer $price
 * @property array $video
 * @property array $images
 * @property array $image
 * @property array $url
 * @property ProductCompany $company
 * @property ProductCategory $category
 * @property Business $business
 * @property Product $tovar
 * @property User $user
 * @property City $city
 * @property string $contact
 * @property integer $views
 * @property integer $total_rating
 * @property integer $quantity_rating
 * @property float $rating
 * @property integer $isShowOnBusiness
 * @property integer $discount
 * @property string $trailer
 * @property string $color
 * @property integer $idProvider
 */
class Ads extends ActiveRecordMongoMultiLang
{
    use GetUserTrait;
    use GetCityTrait;
    use GetBusinessTrait;

    protected static $attributes = [];
    protected static $attributesCategory = [];

    const NOT_DISPLAY_ON_BUSINESS = 1;
    const DISPLAY_ON_BUSINESS = 2;

    public static $statusDisplayOnBusiness = [
        self::NOT_DISPLAY_ON_BUSINESS => 'Нет',
        self::DISPLAY_ON_BUSINESS => 'Да',
    ];

    public static $typeDiscount = [
        '5' => '5%',
        '10' => '10%',
        '15' => '15%',
        '20' => '20%',
        '25' => '25%',
        '30' => '30%',
        '35' => '35%',
        '40' => '40%',
        '45' => '45%',
        '50' => '50%',
        '55' => '55%',
        '60' => '60%',
        '65' => '65%',
        '70' => '70%',
        '75' => '75%',
        '80' => '80%',
        '85' => '85%',
        '90' => '90%',
        '95' => '95%',
    ];

    /**
     * @var array
     * Используется как поле модели при добавлении массива изображений в модель File,
     * а так же при считывании заполняется этими файлами в методе this->afterFind().
     */
    public $images = [];

    public static function findModel($id)
    {
        return Ads::find()->where(['_id' => $id])->one();
    }

    public function findModelByUser($id)
    {
        $model = Ads::find();
        if (Yii::$app->user->identity->role == User::ROLE_EDITOR) {
            $model = $model->where(['_id' => $id]);
        } else {
            $model = $model->where(['_id' => $id, 'idUser' => Yii::$app->user->identity->id]);
        }

        return $model->one();
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => Slug::className(),
                'in_attribute' => 'title',
                'out_attribute' => 'url',
                'translit' => true,
                'mongo' => true,
                'max_len' => 200,
            ],
            'ImageUpload' => [
                'class' => ImageUpload::className(),
                'attribute' => ['image', 'images'],
                'id' => '_id',
                'pid' => 'pidMongo',
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title', 'idCity', 'price', 'description', 'idCategory', 'contact'], 'required', 'message' => 'Поле не может быть пустым'],
            [['idUser', 'idBusiness', 'price', 'views', 'total_rating', 'quantity_rating', 'isShowOnBusiness', 'idProvider', 'discount'], 'integer'],
            [['rating'], 'double'],
            [['title', 'seo_description', 'seo_keywords', 'seo_title', 'contact'], 'string'],
            [['model', 'dateCreate'], 'string', 'max' => 255],
            [['image'], 'file', 'maxSize' => Yii::$app->params['maxSizeUpload'], 'extensions'=>'jpg, gif, png'],
            [['description'], 'string', 'min' => 50, 'message' => 'Описание должно содержать минимум 50 символов.'],
            [['gallery', 'video', 'dateCreate', 'url', 'idCategory', 'contact', 'idCompany'], 'safe'],
            [['color'], 'string'],
            [['trailer'], 'string','max' => 50, 'min' => 11],
            [['title'], 'required', 'on' => 'create'],
        ];
    }

    /**
     * @return AdsQuery
     */
    public static function find()
    {
        return Yii::createObject(AdsQuery::className(), [get_called_class()]);
    }

    public function beforeValidate()
    {
        if (!$this->idCity and $this->isNewRecord and $this->idBusiness) {
            $model = Business::find()->select(['idCity'])->where(['id' => $this->idBusiness])->one();
            if ($model) {
                $this->idCity = (int)$model->idCity;
            }
        } elseif (!$this->idCity and !empty($this->business)) {
            $this->idCity = (int)$this->business->city->id;
        }
        return parent::beforeValidate();
    }

    public function initAttrForCategory($cat, $values = null)
    {
        $cfv = ProductCustomfield::find()->where(['idCategory' => $cat])->select(['alias'])->asArray()->all();
        $cfv = ArrayHelper::getColumn($cfv, 'alias');
        static::$attributesCategory = [$cat => $cfv];
        static::$attributes = array_merge($this->attributeNames(), $cfv);
        if (is_array($values)) {
            foreach ($values as $name => $value) {
                if (in_array($name, static::$attributes)) {
                    $this->{$name} = $value;
                }
            }
        }
    }

    public function setAttributes($values, $safeOnly = true)
    {
        if (is_array($values)) {
            $attributes = array_flip($this->attributes());
            foreach ($values as $name => $value) {
                if (isset($attributes[$name])) {
                    $this->$name = $value;
                }
            }
        }
    }

    public function attributes()
    {
        if (!static::$attributes) {
            $cfv = ProductCustomfield::find()->select(['alias', 'idCategory'])->asArray()->all();
            foreach ($cfv as $cf) {
                static::$attributesCategory[$cf['idCategory']][] = $cf['alias'];
            }
            static::$attributes = array_merge($this->attributeNames(), ArrayHelper::getColumn($cfv, 'alias'));
        }
        return static::$attributes;
    }

    protected function attributeNames()
    {
        return [
            '_id',
            'title',
            'url',
            'image',
            'gallery',
            'idCategory',
            'idCompany',
            'idUser',
            'idBusiness',
            'idCity',
            'description',
            'dateCreate',
            'model',
            'price',
            'video',
            'idYandex',
            'seo_description',
            'seo_keywords',
            'seo_title',
            'contact',
            'views',
            'total_rating',
            'quantity_rating',
            'rating',
            'isShowOnBusiness',
            'discount',
            'trailer',
            'color',
            'idProvider',
        ];
    }

    public function beforeSave($insert)
    {
        $this->title = strip_tags($this->title);
        $this->idCategory = (int)$this->idCategory;
        $this->idCompany = (int)$this->idCompany;
        $this->idBusiness = (int)$this->idBusiness;
        $this->price = (int)$this->price;
        $this->idUser = (int)$this->idUser;
        $this->idCity = (int)$this->idCity;
        $this->dateCreate = date('d-m-Y');

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes = null)
    {
        $gallery = Gallery::findOne(['title' => (string)$this->_id]);
        if (!$gallery) {
            $gallery = new Gallery();
            $gallery->type = File::TYPE_ADS_GALLERY;
            $gallery->pid = 0;
            $gallery->title = (string)$this->_id;
            $gallery->save();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeDelete()
    {
        AdsColor::deleteAll(['idAds' => $this->_id]);
        if (parent::beforeDelete()) {
            $this->delGallery((string)$this->_id);
            return true;
        } else {
            return false;
        }
    }

    protected function delGallery($id)
    {
        $model = Gallery::find()->where(['type' => File::TYPE_ADS_GALLERY, 'title' => $id])->one();
        $files = File::find()->where(['type' => File::TYPE_GALLERY, 'pidMongo' => (string)$this->_id])->all();
        if (!empty($files)) {
            $listFiles = [];
            foreach ($files as $item) {
                $listFiles[] = $item->name;
                $item->delete();
            }
            Yii::$app->files->deleteFilesGallery(
                $model,
                'attachments',
                $listFiles,
                null,
                self::collectionName() . '/' . $model->id
            );
        }
        if ($model) {
            $model->delete();
        }
    }

    public static function collectionName()
    {
        return 'ads';
    }

    public function afterFind()
    {
        if (empty($this->video)) {
            $this->video = [];
        }
        //Получаем изображения прикрепленные к объявлению из таблицы file
        $images = $this->hasMany(File::className(), ['pidMongo' => '_id']);
        /**
         * @var ActiveQuery $images
         */
        $images = $images->andWhere(['type' => File::TYPE_ADS_IMAGES])->select('name')->asArray()->all();
        /**
         * @var array $images
         */
        $this->images = ArrayHelper::getColumn($images, 'name');
        parent::afterFind();
    }

    public function translateAttributes()
    {
        return [
            'title',
            'description',
            'seo_description',
            'seo_keywords',
            'seo_title',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'idCategory']);
    }
    
    public function getCompany()
    {
        return $this->hasOne(ProductCompany::className(), ['id' => 'idCompany']);
    }

    public function getBusiness()
    {
        return $this->hasOne(Business::className(), ['id' => 'idBusiness']);
    }

    public function getTovar()
    {
        return $this->hasOne(Product::className(), ['_id' => 'model'])->select(['model']);
    }

    public function getProvider(){
        return $this->hasOne(Provider::className(), ['id' => 'idProvider']);
    }

    public function getComments($id)
    {
        $count = Comment::find()->where(['type' => File::TYPE_ADS, 'pidMongo' => $id])->asArray()->count();
        return ($count)? $count : 0;
    }

    public function getTitleCustomfield($alias)
    {
        $model = ProductCustomfield::find()->select('title')->where(['alias' => $alias])->one();
        return ($model)? $model->title : '';
    }

    public function getFrontendUrl()
    {
        return '/ads/' . $this->_id;
    }

    public function getSubDomain()
    {
        return !empty($this->city) ? ($this->city->subdomain . '.') : null;
    }

    public function getSearchShortView()
    {
        return '/ads/_ads_short';
    }
}
