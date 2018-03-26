<?php

namespace common\extensions\ViewCounter;

use common\models\Ads;
use InvalidArgumentException;
use yii\base\Widget;

class AdsViewCounter extends Widget
{
    /**
     * Объект или id
     * @var string|Ads $item
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
            $this->item = Ads::findModel($this->item);

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
