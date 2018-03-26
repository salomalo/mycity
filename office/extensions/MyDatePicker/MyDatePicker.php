<?php
namespace office\extensions\MyDatePicker;

use common\models\Lang;
use InvalidArgumentException;
use yii\base\Widget;
use yii\db\ActiveRecord;

class MyDatePicker extends Widget
{
    const LANG_RU = 'ru-RU';
    const LANG_UK = 'uk-Uk';

    /** @var array $fields */
    public $fields;

    /** @var array $disabled */
    public $disabled = [];

    /** @var ActiveRecord $model */
    public $model;

    public function init()
    {
        parent::init();
        MyDatePickerAssets::register($this->view);
        if ($lang = Lang::getCurrent()) {
            switch ($lang->local) {
                case self::LANG_RU:
                    MyDatePickerLangRuAssets::register($this->view);
                    break;
                case self::LANG_UK:
                    MyDatePickerLangUkAssets::register($this->view);
                    break;
            }
        }
    }

    public function run()
    {
        $available_fields = array_merge(array_keys($this->model->attributes), array_keys(get_object_vars($this->model)));
        $fields = array_keys($this->fields);

        $incorrect = [];
        foreach ($fields as $field) {
            if (!in_array($field, $available_fields)) {
                $incorrect[] = $field;
            }
        }
        if ($incorrect) {
            $incorrect = implode(', ', $incorrect);
            $class = get_class($this->model);

            throw new InvalidArgumentException("fields: $incorrect don't exist in $class");
        }

        return $this->render('main', [
            'model' => $this->model,
            'model_name' => $this->getModelName(),
            'inputs' => $this->fields,
            'disabled' => $this->disabled,
        ]);
    }

    private function getModelName()
    {
        $class = explode('\\', get_class($this->model), 3);
        return (!empty($class) and isset($class[count($class) - 1])) ? $class[count($class) - 1] : null;
    }
}