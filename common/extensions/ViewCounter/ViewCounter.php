<?php

namespace common\extensions\ViewCounter;

use common\models\ViewCount;
use yii;
use yii\base\Widget;

class ViewCounter extends Widget
{
    /** @var integer $item */
    public $item;

    /** @var integer $type */
    public $type;

    /** @var bool $count */
    public $count = true;

    /** @var int|null $year */
    public $year = null;

    /** @var int|null $month */
    public $month = null;
    
    /** @var bool $total */
    public $total = true;

    /** @var ViewCount $object */
    private $object;
    
    /** @var int $value */
    private $value;

    public function init()
    {
        if (is_null($this->month)) {
            $this->month = date('m');
        }
        if (is_null($this->year)) {
            $this->year = date('Y');
        }
        $this->object = ViewCount::findModel($this->item, $this->type, $this->year, $this->month);
        $this->value = $this->total ? ViewCount::countTotal($this->item, $this->type) : $this->object->value;

        parent::init();
    }

    public function run()
    {
        if ($this->count) {
            $this->object->value++;
            $this->object->updateAttributes(['value']);
            $this->value++;
        }
        return $this->value;
    }
}
