<?php

namespace common\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;
use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;
use common\components\ActiveRecordMultiLang;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "business_item_category".
 *
 * @mixin NestedSetsBehavior
 * @mixin NestedSetsQueryBehavior
 * @property integer $id
 * @property integer $root
 * @property integer $depth
 * @property integer $lft
 * @property integer $rgt
 * @property integer $pid
 * @property string $title
 * @property string $url
 * @property string $image
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 * @property ProductCompany[] $companies
 * @property string $description
 * @property string $hide_description
 */
class ProductCategory extends ActiveRecordMultiLang
{
    public $pid;
    public $parent;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
            return 'product_category';
    }

    public function behaviors()
    {
         return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'root',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'depthAttribute' => 'depth',
            ],
            'slug' => [
                'class' => 'common\behaviors\Slug',
                'in_attribute' => 'title',
                'out_attribute' => 'url',
                'translit' => true,
                'mongo' => false
            ]
        ];       
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @return ProductCategoryQuery
     */
    public static function find()
    {
        return new ProductCategoryQuery(get_called_class());
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required', 'message' => 'Поле не может быть пустым'],
            [['url'], 'string', 'max' => 255],
            [['image'], 'file', 'maxSize' => Yii::$app->params['maxSizeUpload'], 'extensions'=>'jpg, gif, png'],
            [['title', 'seo_description', 'seo_keywords', 'seo_title', 'sitemap_en'], 'string'],
            [['description', 'hide_description'], 'string'],
            [['root', 'depth', 'lft', 'rgt', 'pid'], 'safe'],
        ];
    }
   
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'title' => 'Title',
            'image' => 'Image',
            'root' => 'Root',
            'depth' => 'Depth',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'seo_description'=>'SEO Description',
            'seo_keywords'=>'SEO Ключевые слова',
            'seo_title' => 'SEO Title',
            'description' => 'Описание',
            'hide_description' => 'Скрытое описание',
            
        ];
    }
    
    public function translateAttributes()
    {
        return [
            'title',
            'seo_description',
            'seo_keywords',
            'seo_title'
        ];
    }
    
    public static function categoryTitle($id)
    {
        $model = self::find()->select(['title'])->where(['id' => $id])->one();
        if($model){
            return $model->title;
        }
        else  return '';
    }

    public static function getAll($idCategory = null)
    {
        $rootcategory = self::findOne(['id'=>(int)$idCategory]);
        if($rootcategory){
            $idCategory = (!$rootcategory->isRoot()) ? $rootcategory->parents()->one()->id : $idCategory;
            $listProdCat = ProductCompany::find()
                ->leftJoin('ProductCategoryCategory','"ProductCategoryCategory"."ProductCompany" = product_company.id')
                ->where(['ProductCategoryCategory.ProductCategory' => (int)$idCategory])
                ->select(['"product_company"."id"', '"product_company"."title"'])
                ->all();
        } else {
            $listProdCat = ProductCompany::find()
                ->leftJoin('ProductCategoryCategory','"ProductCategoryCategory"."ProductCompany" = product_company.id')
                ->select(['"product_company"."id"', '"product_company"."title"'])
                ->limit(100)
                ->all();
        }
        return ArrayHelper::map($listProdCat,'id','title');
    }

    public static function getLeaves(){
        return ArrayHelper::map(self::find()->orderBy(['title'=>'DESC'])->leaves()->all(),'id','title');
    }

    public function afterSave($insert, $changedAttributes = null)
    {
         
        $file = File::find()->where(['name' => $this->image])->one();
        if($file){
            $file->pid = $this->id;
            $file->update();
        }
        parent::afterSave($insert, $changedAttributes);
     }

    public static function getCategoryList()
    {
         $list = [];

         foreach (self::find()->roots()->orderBy('title ASC')->batch(100) as $b){
             foreach ($b as $item){
                 $list[$item->id] = $item->title;
                 foreach ($item->children()->addOrderBy('title')->all() as $ch){
                     $pref = ' ';
                     for ($i=1; $i <= $ch->depth; $i++){
                         $pref .= '-';
                     }
                     $list[$ch->id] = $pref . $ch->title;
                 }
             }
         }

         return $list;
     }

    /**
     * Массив id => title для select2
     *
     * @return array
     */
    public static function getCategoryArray()
    {
        /** @var ProductCategory[] $productCategories */
        $productCategories  = ProductCategory::find()->roots()->all();

        $out = [];
        foreach ($productCategories as $category) {
            $out[$category->id] = $category->title;

            /** @var ProductCategory[] $children */
            $children = $category->children(1)->all();

            foreach ($children as $child) {
                $out[$child->id] = "-$child->title";

                /** @var ProductCategory[] $others */
                $others = $child->children()->all();

                foreach ($others as $other) {
                    $out[$other->id] = "--$other->title";
                }
            }
        }

        return $out;
    }

    /**
     * @return ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasMany(ProductCompany::className(), ['id' => 'ProductCompany'])->viaTable('ProductCategoryCategory', ['ProductCategory' => 'id']);
    }
}

/**
 * ProductCategoryQuery
 *
 * @mixin NestedSetsQueryBehavior
 */
class ProductCategoryQuery extends ActiveQuery
{
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
    public static function find()
    {
        return new ProductCategoryQuery(get_called_class());
    }
}
