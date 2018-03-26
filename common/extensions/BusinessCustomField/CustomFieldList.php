<?php
namespace common\extensions\BusinessCustomField;

use common\models\Business;
use yii;
use yii\base\Widget;

/**
 * Class CustomFieldList
 * @package common\extensions\BusinessCustomField
 *
 * @property array $customField
 */
class CustomFieldList extends Widget
{
    /** @var Business $business */
    public $business;

    public function init()
    {
        parent::init();

        CustomFieldListAssets::register($this->view);
    }

    public function run()
    {
        return $this->customField ? $this->render('list/main', ['cf' => $this->customField]) : null;
    }

    public function getCustomField()
    {
        $cf = [];
        if (!empty($this->business->customFieldValues)) {
            foreach ($this->business->customFieldValues as $item) {
                $cf[$item->customField->title][] = $item->anyValue;
            }
            foreach ($cf as &$item) {
                $item = implode(', ', $item);
            }
        }

        return $cf;
    }
}