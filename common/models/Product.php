<?php
namespace common\models;

use common\components\ActiveRecordMongoMultiLang;
use common\models\traits\GetCityTrait;
use common\models\traits\GetUserTrait;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Product
 * @package common\models
 * @property string $_id
 * @property string $title
 * @property string $image
 * @property string $url
 * @property integer $idCategory
 * @property integer $idCompany
 * @property integer $idYandex
 * @property string $model
 * @property string $description
 * @property string $seo_description
 * @property string $seo_keywords
 * @property City $city
 * @property ProductCategory $category
 * @property ProductCompany $company
 * @property ProductCompany[] $companies
 * @property CountViews $countView
 * @property integer $views
 * @property integer $total_rating
 * @property integer $quantity_rating
 * @property double $rating
 */
class Product extends ActiveRecordMongoMultiLang
{
    use GetCityTrait;
    use GetUserTrait;

    protected static $attributes = [];
    protected static $attributesCategory = [];

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => 'common\behaviors\Slug',
                'in_attribute' => 'title',
                'out_attribute' => 'url',
                'translit' => true,
                'mongo' => true
            ]
        ];
    }  

    public static function collectionName()
    {
        return 'product';
    }

    public function rules()
    {
        return [
            [['idCategory', 'idCompany'], 'required', 'message' => 'Поле не может быть пустым'],
            [['idCategory', 'idCompany', 'views', 'total_rating', 'quantity_rating'], 'integer'],
            [['rating'], 'double'],
            [['title', 'description', 'seo_description', 'seo_keywords', 'seo_title'], 'string'],
            [['model'], 'string', 'max' => 255],
            [['image'], 'file', 'maxSize' => Yii::$app->params['maxSizeUpload'], 'extensions'=>'jpg, gif, png'],
            [['gallery', 'video', 'url', 'dateUpdate', 'sitemap_en', 'sitemap_priority', 'sitemap_changefreq'], 'safe'],
            [['title'], 'required', 'on' => 'create'],
        ];
    }

    public function attributes()
    {
        if ((int)$this->idCategory) {
            if (!isset(static::$attributesCategory[(int)$this->idCategory])) {
                $cfv = ProductCustomfield::find()->where(['idCategory' => $this->idCategory])->select('alias')->asArray()->all();
                static::$attributesCategory[(int)$this->idCategory] = ArrayHelper::merge($this->attributeNames(), ArrayHelper::getColumn($cfv, 'alias'));
            }
            return static::$attributesCategory[(int)$this->idCategory];
        }
        return $this->attributeNames();
    }

    public function hasAttribute($name)
    {
        if ($this->getAttribute('idCategory'))
            return parent::hasAttribute($name);

        return true;
    }

    public function setAttributes($values, $safeOnly = true)
    {
        if (is_array($values)) {
            $attributes = array_flip($this->attributes());
            if (isset($values['idCategory'])) {
                $this->idCategory = $values['idCategory'];
            }
            if ($this->idCategory) {
                foreach ($values as $name => $value) {
                    if (isset($attributes[$name])) {
                        $this->$name = $value;
                    }
                }
            }else {
                foreach ($values as $name => $value) {
                    $this->$name = $value;
                }
            }
        }
    }

    public static function populateRecord($record, $row)
    {
        if (!empty($row['idCategory']))
        {
            $record->idCategory = $row['idCategory'];
        }
        parent::populateRecord($record, $row);
    }

    public function beforeSave($insert)
    {
        $this->idCategory = (int)$this->idCategory;
        $this->idCompany = (int)$this->idCompany;
        $this->dateUpdate = date('Y-m-d');
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes = null)
    {
        // Remove keys that not belong to category
        $attributes = array_keys($this->getAttributes());
        $catAttributes = isset(static::$attributesCategory[$this->idCategory]) ? static::$attributesCategory[$this->idCategory] : [];
        $removeKeys = array_diff($attributes, $catAttributes, $this->attributeNames());
        if (count($removeKeys) > 0)
        {
            $values = [];
            foreach($removeKeys as $key)
            {
                $values[$key] = "";
            }

            $collection = \Yii::$app->mongodb->getCollection('product');
            $collection->update(['_id' => $this->_id], [
                '$unset' => $values
            ]);
        }
        
        $file = File::find()->where(['name' => $this->image])->one();
        if($file){
            $file->pidMongo = $this->_id;
            $file->update();
        }
        
        $files = File::find()->where(['name' => $this->gallery])->all();
        if($files){
            foreach ($files as $item){
                $item->pidMongo = $this->_id;
                $item->update();
            }   
        }
    }

    /////////////// Part for adding additional finctional

    protected function attributeNames()
    {
        return [
            '_id', 'title', 'url', 'image', 'gallery', 'idCategory', 'idCompany', 'description', 'model', 'video',
            'idYandex', 'seo_description', 'seo_keywords', 'seo_title', 'dateUpdate', 'sitemap_en', 'sitemap_priority',
            'sitemap_changefreq', 'views', 'total_rating', 'quantity_rating', 'rating',
        ];
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

    public function getCompanies()
    {
        return $this->hasMany(ProductCompany::className(), ['id' => 'idCompany']);
    }

    public function getCountView()
    {
        return $this->hasOne(CountViews::className(), ['pidMongo' => '_id'])->where(['type' => File::TYPE_PRODUCT]);
    }

    public function getAds($id)
    {
        $models = Ads::find()->select(['idBusiness', 'price'])->where(['model' => (string)$id])->all();
        return $models ? $models : null;
    }

    public function getCountAds($id)
    {
        return Ads::find()->select(['idBusiness', 'price'])->where(['model' => $id.''])->count();
    }

    public function getComments($id)
    {
        $count = Comment::find()->where(['type' => File::TYPE_PRODUCT, 'pidMongo' => $id])->asArray()->count();
        return ($count)? $count : 0;
    }

    public function getTitleCustomfield($alias){
        $model = ProductCustomfield::find()->select('title')->where(['alias' => $alias])->one();
        return ($model)? $model->title : '';
    }
    
    /**
     * @param $idCategory integer
     * @return ProductCustomfield[]
     */
    public static function getCustomfieldsModel($idCategory)
    {
        $model = ProductCustomfield::find()
            ->leftJoin('customfield_category', 'product_customfield."idCategoryCustomfield"=customfield_category."id"')
            ->where(['idCategory' => $idCategory])
            ->orderBy(['customfield_category.order' => 'ASC', 'product_customfield.order' => 'ASC'])
            ->all();
        
        return $model;
    }

    public function getFrontendUrl()
    {
        return '/product/' . $this->_id;
    }

    public function getSubDomain()
    {
        return !empty($this->city) ? ($this->city->subdomain . '.') : null;
    }
}
