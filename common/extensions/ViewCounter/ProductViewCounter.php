<?php

namespace common\extensions\ViewCounter;

use common\models\Product;
use InvalidArgumentException;
use yii\base\Widget;

class ProductViewCounter extends Widget
{
    /**
     * Объект или id
     * @var string|Product $item
     */
    public $item = null;

    /**
     * Производить ли пересчет счетчиков любых
     * @var bool $count
     */
    public $count = true;

    public function init()
    {
        if (is_string($this->item)) {
            $this->item = Product::find()->where(['_id' => $this->item])->one();

            if (!$this->item) {
                throw new InvalidArgumentException('object does not exist ' . $this->item);
            }
        } elseif (!is_object($this->item)) {
            throw new InvalidArgumentException('item must be object or string id, given ' . gettype($this->item));
        }
    }

    public function run()
    {
        if ($this->count) {
            $this->item->updateCounters(['views' => 1]);
        }

        return $this->item->views;
    }
}
