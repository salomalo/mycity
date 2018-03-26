<?php

namespace common\models;

use InvalidArgumentException;
use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "view_count".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $category
 * @property integer $value
 * @property integer $year
 * @property integer $month
 * @property integer city_id
 * @property string $categoryLabel
 * @property string $itemTitle
 * @property Business|BusinessCategory $model
 * @property null|City $city
 */
class ViewCount extends ActiveRecord
{
    const CAT_BUSINESS = 101;
    const CAT_BUSINESS_CATEGORY = 201;

    public static $categories = [
        self::CAT_BUSINESS,
        self::CAT_BUSINESS_CATEGORY,
    ];

    public static $labels = [
        self::CAT_BUSINESS => 'Предприятие',
        self::CAT_BUSINESS_CATEGORY => 'Категория предприятия',
    ];

    public $sum;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'view_count';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'category', 'value', 'year', 'month', 'city_id'], 'integer'],
            [['item_id', 'category', 'value', 'year', 'month'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'Запись',
            'category' => 'Тип',
            'value' => 'Значение',
            'year' => 'Год',
            'month' => 'Месяц',
            'city_id' => 'Город',
        ];
    }

    /**
     * @param integer $item
     * @param integer $cat
     * @param integer $year
     * @param integer $month
     * @return ViewCount
     */
    public static function findModel($item, $cat, $year, $month)
    {
        if (!in_array($cat, self::$categories)) {
            throw new InvalidArgumentException('Wrong category!');
        }
        $object = self::find()->where([
            'item_id' => $item,
            'category' => $cat,
            'year' => $year,
            'month' => $month,
        ])->one();

        if (!$object) {
            $object = new self([
                'item_id' => $item,
                'category' => $cat,
                'value' => 0,
                'year' => $year,
                'month' => $month,
            ]);
            $object->save();
        }
        return $object;
    }

    /**
     * @param integer $item
     * @param integer $cat
     * @return integer
     */
    public static function countTotal($item, $cat)
    {
        if (!in_array($cat, self::$categories)) {
            throw new InvalidArgumentException('Wrong category!');
        }
        $value = self::find()->where(['item_id' => $item, 'category' => $cat])->sum('value');
        return (int)$value;
    }

    /**
     * @param $cat int
     * @return array
     */
    public static function getAllByType($cat)
    {
        if (!in_array($cat, self::$categories)) {
            throw new InvalidArgumentException('Wrong category!');
        }
        $value = self::find()->select(['item_id', 'SUM(value) AS sum'])->where(['category' => self::CAT_BUSINESS_CATEGORY])->groupBy(['item_id'])->all();
        return $value;
    }

    public function getCategoryLabel()
    {
        return isset(self::$labels[$this->category]) ? self::$labels[$this->category] : $this->category;
    }

    public function getModel()
    {
        $labels = [
            self::CAT_BUSINESS => Business::className(),
            self::CAT_BUSINESS_CATEGORY => BusinessCategory::className(),
        ];
        return isset($labels[$this->category]) ? $this->hasOne($labels[$this->category], ['id' => 'item_id']) : null;
    }

    /**
     * @return string
     */
    public function getItemTitle()
    {
        $result = null;
        if (!empty($this->model)) {
            $result = $this->model->title;
        }
        return $result;
    }

    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    public function beforeValidate()
    {
        if (is_object($this->model) and (Business::className() === $this->model->className())) {
            $this->city_id = $this->model->idCity;
        }
        return parent::beforeValidate();
    }
}
