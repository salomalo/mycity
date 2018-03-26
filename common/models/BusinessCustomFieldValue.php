<?php

namespace common\models;

use common\components\ActiveRecordMultiLang;
use yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "business_custom_field_value".
 *
 * @property integer $id
 * @property integer $business_id
 * @property integer $custom_field_id
 * @property integer $value_id
 * @property string $value
 * @property float $value_numb
 *
 * @property Business $business
 * @property BusinessCustomField $customField
 * @property BusinessCustomFieldDefaultVal $valueByDefId
 * @property string $anyValue
 */
class BusinessCustomFieldValue extends ActiveRecordMultiLang
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business_custom_field_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_id', 'custom_field_id', 'value_id'], 'integer'],
            [['value_numb'], 'number'],
            [['value'], 'string', 'max' => 255],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::className(), 'targetAttribute' => ['business_id' => 'id']],
            [['custom_field_id'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessCustomField::className(), 'targetAttribute' => ['custom_field_id' => 'id']],
            [['value_id'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessCustomFieldDefaultVal::className(), 'targetAttribute' => ['value_id' => 'id']],
            [['custom_field_id', 'business_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('business_custom_field', 'ID'),
            'business_id' => Yii::t('business_custom_field', 'Business ID'),
            'custom_field_id' => Yii::t('business_custom_field', 'Custom Field ID'),
            'value_id' => Yii::t('business_custom_field', 'Value ID'),
            'value' => Yii::t('business_custom_field', 'Value'),
            'value_numb' => Yii::t('business_custom_field', 'Value in number'),
        ];
    }

    public function translateAttributes()
    {
        return ['value'];
    }

    /**
     * @return ActiveQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(Business::className(), ['id' => 'business_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCustomField()
    {
        return $this->hasOne(BusinessCustomField::className(), ['id' => 'custom_field_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getValueByDefId()
    {
        return $this->hasOne(BusinessCustomFieldDefaultVal::className(), ['id' => 'value_id']);
    }

    public function getAnyValue()
    {
        return empty($this->value) ? (empty($this->valueByDefId) ? null : $this->valueByDefId->value) : $this->value;
    }

    public function beforeSave($insert)
    {
        if ($float_val = floatval($this->value)) {
            $this->value_numb = $float_val;
        }
        return parent::beforeSave($insert);
    }
}
