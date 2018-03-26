<?php

namespace common\models;

use common\components\ActiveRecordMultiLang;
use yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "business_custom_field".
 *
 * @property integer $id
 * @property string $title
 * @property integer $multiple
 * @property integer $filter_type
 *
 * @property BusinessCategoryCustomFieldLink[] $businessCategoryLinks
 * @property BusinessCustomFieldDefaultVal[] $defaultValues
 * @property BusinessCustomFieldValue[] $allCustomValues
 * @property BusinessCustomFieldValue[] $businessCustomFieldValues
 * @property boolean $hasDefault
 * @property array $defaultValuesForForm
 * @property array $allValuesForForm
 * @property BusinessCustomFieldValue[] $customFieldValues
 */
class BusinessCustomField extends ActiveRecordMultiLang
{
    const MULTIPLE_FALSE = 0;
    const MULTIPLE_TRUE = 1;

    public static $multiple = [
        self::MULTIPLE_FALSE => 'Одино значение',
        self::MULTIPLE_TRUE => 'Несколько значений',
    ];

    const FILTER_SELECT = 1;
    const FILTER_CHECKBOXES = 2;
    const FILTER_SINGLE_INPUT = 3;
    const FILTER_DOUBLE_INPUT = 4;

    public static $filter_types = [
        self::FILTER_SELECT => 'Выпадающий список',
        self::FILTER_CHECKBOXES => 'Отметка каждого отдельного параметра',
        self::FILTER_SINGLE_INPUT => 'Текстовый ввод значения - 1 поле',
        self::FILTER_DOUBLE_INPUT => 'Текстовый ввод интервала - 2 поля',
    ];

    /** @var array $business_categories */
    public $business_categories;

    /** @var array $default_values */
    public $default_values;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business_custom_field';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['multiple', 'filter_type'], 'integer'],
            [['business_categories', 'default_values'], 'safe'],
            [['title'], 'string', 'max' => 500],
            [['multiple', 'filter_type', 'title'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('business_custom_field', 'ID'),
            'title' => Yii::t('business_custom_field', 'Title'),
            'multiple' => Yii::t('business_custom_field', 'Multiple'),
            'business_categories' => Yii::t('business_custom_field', 'Business Categories'),
            'default_values' => Yii::t('business_custom_field', 'Default Values'),
            'filter_type' => Yii::t('business_custom_field', 'Filter Type'),
        ];
    }

    public function translateAttributes()
    {
        return ['title'];
    }

    /**
     * @return ActiveQuery
     */
    public function getBusinessCategoryLinks()
    {
        return $this->hasMany(BusinessCategoryCustomFieldLink::className(), ['custom_field_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDefaultValues()
    {
        return $this->hasMany(BusinessCustomFieldDefaultVal::className(), ['custom_field_id' => 'id']);
    }

    public function getAllCustomValues()
    {
        return $this->hasMany(BusinessCustomFieldValue::className(), ['custom_field_id' => 'id'])->where(['value_id' => null]);
    }

    public function getDefaultValuesForForm()
    {
        return ArrayHelper::map($this->defaultValues, 'id', 'value');
    }

    public function getAllValuesForForm()
    {
        return ArrayHelper::map($this->allCustomValues, 'id', 'value');
    }

    /**
     * @return bool
     */
    public function getHasDefault()
    {
        return !empty($this->defaultValues);
    }

    /**
     * @return ActiveQuery
     */
    public function getBusinessCustomFieldValues()
    {
        return $this->hasMany(BusinessCustomFieldValue::className(), ['custom_field_id' => 'id']);
    }

    public function saveBusinessCategories()
    {
        if (empty($this->business_categories) or !is_array($this->business_categories)) {
            foreach ($this->businessCategoryLinks as $link) {
                $link->delete();
            }
        } else {
            $links = [];
            foreach ($this->businessCategoryLinks as $link) {
                if (in_array($link->business_category_id, $this->business_categories)) {
                    $links[$link->business_category_id] = $link;
                } else {
                    $link->delete();
                }
            }
            foreach ($this->business_categories as $category) {
                $category = (int)$category;
                if (!isset($links[$category])) {
                    $this->addNewCategoryLink($category);
                }
            }
        }
    }

    /**
     * @param $category integer
     */
    private function addNewCategoryLink($category)
    {
        $link = new BusinessCategoryCustomFieldLink(['business_category_id' => $category, 'custom_field_id' => $this->id]);
        $link->save();
    }

    private function addNewDefaultValue($value)
    {
        $link = new BusinessCustomFieldDefaultVal(['value' => $value, 'custom_field_id' => $this->id]);
        $link->save();
    }

    public function saveDefaultValues()
    {
        if (empty($this->default_values) or !is_array($this->default_values)) {
            foreach ($this->defaultValues as $valueObj) {
                $valueObj->delete();
            }
        } else {
            $values = [];
            foreach ($this->defaultValues as $valueObj) {
                if (in_array($valueObj->value, $this->default_values)) {
                    $values[$valueObj->value] = $valueObj;
                } else {
                    $valueObj->delete();
                }
            }
            foreach ($this->default_values as $value) {
                $value = (string)$value;
                if (!isset($values[$value])) {
                    $this->addNewDefaultValue($value);
                }
            }
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->saveBusinessCategories();
        $this->saveDefaultValues();
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->business_categories = $this->getBusinessCategoryLinks()->select('business_category_id')->column();
        $this->default_values = ArrayHelper::getColumn($this->defaultValues, 'value');
    }

    public function addNewValue($business_id, $value)
    {
        $object = [
            'business_id' => $business_id,
            'custom_field_id' => $this->id,
        ];
        $this->hasDefault ? ($object['value_id'] = (int)$value) : ($object['value'] = (string)$value);

        $valueObj = new BusinessCustomFieldValue($object);
        $valueObj->save();
    }

    public function getCustomFieldValues($business_id)
    {
        return BusinessCustomFieldValue::find()->where(['custom_field_id' => $this->id, 'business_id' => $business_id])->all();
    }

    public function getIdOrValue($business_id)
    {
        $values = $this->getCustomFieldValues($business_id);
        
        return ArrayHelper::getColumn($values, function (BusinessCustomFieldValue $model) {
            return !empty($model->value_id) ? $model->value_id : $model->value;
        });
    }
}
