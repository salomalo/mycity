<?php

namespace common\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "business_product_category".
 *
 * @property integer $id
 * @property integer $business_category_id
 * @property integer $product_category_id
 * @property ProductCategory $product_category
 */
class BusinessProductCategory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business_product_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_category_id'], 'integer'],
            [['business_category_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'business_category_id' => 'ID категории бизнеса',
            'product_category_id' => 'ID категории продуктов',
        ];
    }

    public function getProduct_category()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'product_category_id']);
    }
}
