<?php

namespace common\models;

use yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "business_category_custom_field_link".
 *
 * @property integer $id
 * @property integer $business_category_id
 * @property integer $custom_field_id
 *
 * @property BusinessCategory $businessCategory
 * @property BusinessCustomField $customField
 */
class BusinessCategoryCustomFieldLink extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business_category_custom_field_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_category_id', 'custom_field_id'], 'integer'],
            [['business_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessCategory::className(), 'targetAttribute' => ['business_category_id' => 'id']],
            [['custom_field_id'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessCustomField::className(), 'targetAttribute' => ['custom_field_id' => 'id']],
            [['business_category_id', 'custom_field_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('business_custom_field', 'ID'),
            'business_category_id' => Yii::t('business_custom_field', 'Business Category ID'),
            'custom_field_id' => Yii::t('business_custom_field', 'Custom Field ID'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getBusinessCategory()
    {
        return $this->hasOne(BusinessCategory::className(), ['id' => 'business_category_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCustomField()
    {
        return $this->hasOne(BusinessCustomField::className(), ['id' => 'custom_field_id']);
    }
}
