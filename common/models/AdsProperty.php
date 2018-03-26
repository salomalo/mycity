<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ads_property".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $provider_id
 * @property integer $company_id
 * @property integer $business_id
 * @property integer $user_id
 *
 * @property Business $business
 * @property ProductCategory $category
 * @property ProductCompany $company
 * @property Provider $provider
 * @property User $user
 */
class AdsProperty extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ads_property';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'user_id'], 'required'],
            [['category_id', 'provider_id', 'company_id', 'business_id', 'user_id'], 'integer'],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::className(), 'targetAttribute' => ['business_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCompany::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['provider_id'], 'exist', 'skipOnError' => true, 'targetClass' => Provider::className(), 'targetAttribute' => ['provider_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'provider_id' => 'Provider ID',
            'company_id' => 'Company ID',
            'business_id' => 'Business ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(Business::className(), ['id' => 'business_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(ProductCompany::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvider()
    {
        return $this->hasOne(Provider::className(), ['id' => 'provider_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
