<?php

namespace common\models;

use kartik\tree\models\TreeTrait;
use yii;
use creocoder\nestedsets\NestedSetsBehavior;
use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\ActiveRecord;
use common\components\ActiveRecordMultiLang;

/**
 * This is the model class for table "business_category".
 *
 * @mixin NestedSetsBehavior
 * @mixin NestedSetsQueryBehavior
 * @property integer $id
 * @property integer $pid
 * @property string $title
 * @property string $image
 * @property string $url
 * @property string $update_time
 * @property int $sitemap_en
 * @property string $sitemap_priority
 * @property string $sitemap_changefreq
 * @property string $seo_description
 * @property string $seo_keywords
 * @property string $dateUpdate
 * @property int $lft
 * @property int $rgt
 * @property int $root
 * @property int $depth
 * @property BusinessCustomField[] $customFields
 * @property BusinessCategoryCustomFieldLink[] $businessCategoryCustomFieldLinks
 */
class BusinessCategory extends ActiveRecordMultiLang
{
    use TreeTrait;
    
    /**
     * @var string the classname for the TreeQuery that implements the NestedSetQueryBehavior.
     * If not set this will default to `kartik\tree\models\TreeQuery`.
     */
    public static $treeQueryClass;

    /**
     * @var bool whether to HTML encode the tree node names. Defaults to `true`.
     */
    public $encodeNodeNames = true;

    /**
     * @var bool whether to HTML purify the tree node icon content before saving.
     * Defaults to `true`.
     */
    public $purifyNodeIcons = true;

    /**
     * @var array activation errors for the node
     */
    public $nodeActivationErrors = [];

    /**
     * @var array node removal errors
     */
    public $nodeRemovalErrors = [];
    
    public $pid;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business_category';
    }

    public $productCategoryIds;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid'], 'integer'],
            [['title'], 'required','message' => 'Поле не может быть пустым'],
            [['url', 'dateUpdate','sitemap_en','sitemap_priority','sitemap_changefreq'], 'safe'],
            [['title', 'seo_description','seo_keywords','seo_title'], 'string'],
            [['image'], 'file', 'maxSize' => Yii::$app->params['maxSizeUpload'], 'extensions'=>'jpg, gif, png'],
            [['lft', 'rgt', 'root', 'depth', 'productCategoryIds'], 'safe'],
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
            'seo_description'=>'SEO Description',
            'seo_keywords'=>'SEO Ключевые слова',
            'seo_title' => 'Seo Title',
            'dateUpdate' => 'Дате обновления',
            'sitemap_en' => 'Добавить в sitemap',
            'sitemap_priority' => 'Sitemap приоритет',
            'sitemap_changefreq' => 'Sitemap частота изменения',
            'root' => 'Root',
            'depth' => 'Depth',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'productCategoryIds' => 'Категории продуктов'
            
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

    public function behaviors()
    {
        return array(
            'tree' => [
            'class' => NestedSetsBehavior::className(),
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'treeAttribute' => 'root',
                'depthAttribute' => 'depth'        
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => new Expression('NOW()'),
                'attributes' => array(
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
                    ActiveRecord::EVENT_BEFORE_INSERT => 'update_time',
                ),
            ],
            'slug' => [
                'class' => 'common\behaviors\Slug',
                'in_attribute' => 'title',
                'out_attribute' => 'url',
                'translit' => true,
                'mongo' => false
            ]
        );
    }
    
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
    
    public static function find()
    {
        return new BusinessCategoryQuery(get_called_class());
    }
    
//    public function beforeDelete()
//    {   
//        if (parent::beforeDelete()) {
//            self::deleteAll(['pid'=>$this->id]);
//        return true;
//        } else {
//            return false;
//        }
//        
//    }

    public static function getPodCat($id, &$list){
        $arr = ProductCategory::findOne($id)->children()->orderBy('title ASC')->all();

        foreach ($arr as $a)
        {
            $l='';
            for ($i=0; $i< $a->depth; $i++){
                $l.='-';
            }

            $list[$a->id]= $l.$a->title;

        }
    }
    
    public static function getCategoryList($onlyRoot = false){
         
        $list = [];

        foreach (self::find()->roots()->orderBy('title ASC')->batch(100) as $b){
            foreach ($b as $item){
                $list[$item->id] = $item->title;
//                $list = $list + self::getChildren($item, '', $onlyRoot);
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
    
    public function getCountBusiness()
    {
        $id = '{' . $this->id . '}';
        return Business::find()->where("\"idCategories\" && '" . $id . "'")->count();
    }

    public function beforeDelete()
    {
        $business = Business::find()->where(['@>', 'idCategories', ('{' . $this->id . '}')])->count();
        if ($business > 0) {
            $this->addError('id', 'Категория содержит ' . $business . ' предприятия! Удалите сначала все предприятия из категории!');
            return false;
        } else {
            return parent::beforeDelete();
        }
    }

    public function getCustomFields()
    {
        return $this->hasMany(BusinessCustomField::className(), ['id' => 'custom_field_id'])->via('businessCategoryCustomFieldLinks');
    }

    public function getBusinessCategoryCustomFieldLinks()
    {
        return $this->hasMany(BusinessCategoryCustomFieldLink::className(), ['business_category_id' => 'id']);
    }
}

/**
 * Class BusinessCategoryQuery
 * @package common\models
 * @mixin NestedSetsBehavior
 * @mixin NestedSetsQueryBehavior
 */
class BusinessCategoryQuery extends ActiveQuery
{
    public function behaviors() {
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
        return new BusinessCategoryQuery(get_called_class());
    }
}