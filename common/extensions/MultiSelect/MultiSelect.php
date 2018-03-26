<?php

namespace common\extensions\MultiSelect;

use InvalidArgumentException;
use kartik\base\InputWidget;
use yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;

class MultiSelect extends InputWidget
{
    public $url;
    public $className;
    public $multiple = true;

    public function init()
    {
        parent::init();
        Assets::register($this->view);
    }

    public function run()
    {
        if (empty($this->url)) {
            throw new InvalidArgumentException('Не задан параметр url');
        }

        /**@var ActiveRecord $class */
        $class = $this->className;
        $where = $this->model->{$this->attribute};
        $data = [];

        if (!empty($where)) {
            $where = is_array($where) ? array_filter($where) : $where;
            $data = $class::find()->select(['id', 'title'])->where(['id' => $where])->all();
        }

        $placeholder = isset($this->options['placeholder']) ? $this->options['placeholder'] : 'Сделайте выбор';

        echo $this->render('index', [
            'input' => $this->renderInput(),
            'data' => $data,
            'data_url' => $this->url,
            'placeholder' => $placeholder,
        ]);
    }

    protected function renderInput()
    {
        $attr = $this->attribute;
        if ($this->multiple) {
            $items = [];
            if (is_array($this->model->$attr)) {
                foreach ($this->model->$attr as $item) {
                    $items[$item] = $item;
                }
            }
            $input = Html::activeDropDownList($this->model, $this->attribute, $items, ['multiple' => 'multiple']);
        } else {
            $item = $this->model->$attr;
            $items[$item] = $item;
            $input = Html::activeDropDownList($this->model, $this->attribute, $items, []);
        }

        return $input;
    }
}