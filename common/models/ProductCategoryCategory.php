<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ProductCategoryCategory".
 *
 * @property integer $ProductCategory
 * @property integer $ProductCompany
 * @property ProductCategory $pсategory
 * @property ProductCompany $pcompany
 */
class ProductCategoryCategory extends ActiveRecord
{
    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ProductCategoryCategory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ProductCategory', 'ProductCompany'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ProductCategory' => 'Product Category',
            'ProductCompany' => 'Product Company',
        ];
    }
    
    public function getPcompany()
    {
        return $this->hasOne(ProductCompany::className(), ['id' => 'ProductCompany']);
    }

    public function getPсategory()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'ProductCategory']);
    }
}
