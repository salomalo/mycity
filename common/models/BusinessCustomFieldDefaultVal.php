<?php

namespace common\models;

use common\components\ActiveRecordMultiLang;
use yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "business_custom_field_default_val".
 *
 * @property integer $id
 * @property integer $custom_field_id
 * @property string $value
 * @property float $value_numb
 *
 * @property BusinessCustomField $customFiles
 * @property BusinessCustomFieldValue[] $businessCustomFieldValues
 */
class BusinessCustomFieldDefaultVal extends ActiveRecordMultiLang
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business_custom_field_default_val';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['custom_field_id'], 'integer'],
            [['value_numb'], 'number'],
            [['value'], 'string', 'max' => 255],
            [['custom_field_id'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessCustomField::className(), 'targetAttribute' => ['custom_field_id' => 'id']],
            [['custom_field_id', 'value'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('business_custom_field', 'ID'),
            'custom_field_id' => Yii::t('business_custom_field', 'Custom Field ID'),
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
    public function getCustomFiles()
    {
        return $this->hasOne(BusinessCustomField::className(), ['id' => 'custom_files_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBusinessCustomFieldValues()
    {
        return $this->hasMany(BusinessCustomFieldValue::className(), ['value_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if ($float_val = floatval($this->value)) {
            $this->value_numb = $float_val;
        }
        return parent::beforeSave($insert);
    }
}
