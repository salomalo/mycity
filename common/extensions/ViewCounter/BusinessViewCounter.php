<?php

namespace common\extensions\ViewCounter;

use common\models\BusinessCategory;
use common\models\ViewCount;
use yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 * Class BusinessViewCounter
 * @package common\extensions\ViewCounter
 *
 *
 *
 *
 *
 * @property integer $categoryValue
 * @property integer $itemValue
 */
class BusinessViewCounter extends Widget
{
    /** @var integer|null $item */
    public $item = null;

    /** @var integer|null $item */
    public $category = null;

    /** @var integer[]|null $category */
    public $categories = null;

    /**
     * Пересчитывать ли родительские категории
     * @var bool $with_parents
     */
    public $with_parents = true;

    /**
     * Пересчитывать ли категории
     * @var bool $with_category
     */
    public $with_category = true;

    /**
     * Производить ли пересчет счетчиков любых
     * @var bool $count
     */
    public $count = true;

    /** @var null|int $month */
    public $month = null;

    /** @var null|int $year */
    public $year = null;

    /** @var integer $item_type */
    private $item_type = ViewCount::CAT_BUSINESS;

    /** @var integer $category_type */
    private $category_type = ViewCount::CAT_BUSINESS_CATEGORY;

    public function run()
    {
        $result = 0;

        if ($this->item) {
            $result = $this->itemValue;
        } elseif ($this->category) {
            $result = $this->categoryValue;
        }

        return $result;
    }

    public function getItemValue()
    {
        $this->countCategories();
        $result = 0;
        if (!is_null($this->item)) {
            $result = $this->getCounter($this->item, $this->item_type);
        }
        return $result;
    }

    private function countCategories()
    {
        if ($this->with_category and is_array($this->categories) and $this->count) {
            $array = [];

            foreach ($this->categories as $category_id) {
                /** @var BusinessCategory $category */
                $category = BusinessCategory::find()->where(['id' => $category_id])->one();
                if ($category) {
                    $array[] = $category->id;
                    if ($this->with_parents) {
                        /** @var BusinessCategory[] $parents */
                        $parents = $category->parents()->all();
                        if (is_array($parents)) {
                            foreach ($parents as $item) {
                                $array[] = $item->id;
                            }
                        }
                    }
                }
            }
            $array = array_unique(array_filter($array));
            /** @var int[] $array */
            foreach ($array as $item) {
                $this->getCounter($item, $this->category_type);
            }
        }
    }

    public function getCategoryValue()
    {
        $result = 0;
        if (!is_null($this->category)) {
            /** @var BusinessCategory $category */
            $category = BusinessCategory::find()->where(['id' => $this->category])->one();
            if ($category) {
                $result = $this->getCounter($this->category, $this->category_type);
                if ($this->with_parents and $this->count) {
                    $parents = $category->parents()->select('id')->all();
                    if ($parents) {
                        $parents = array_unique(ArrayHelper::getColumn($parents, 'id'));
                        foreach ($parents as $item) {
                            $this->getCounter($item, $this->category_type);
                        }
                    }
                }
            }
        }
        return $result;
    }

    private function getCounter($item, $type)
    {
        return (int)ViewCounter::widget([
            'item' => $item,
            'type' => $type,
            'count' => $this->count,
            'month' => $this->month,
            'year' => $this->year,
        ]);
    }
}
