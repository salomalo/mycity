<?php

namespace common\extensions\MultiSelect2;

use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\extensions\MultiSelect2\Assets;


class MultiSelect2 extends Select2 {
     // public $placeholder ="Выберите категорию";
    public $url;
    public $className;
    public $classDepDropName = null;
    public $model;
    public $isNested=true;
    public $pluginEvents = [
//            "select2:select" => "function() { fun(); }",
        ];

    public function init()
    {
        $this->pluginEvents = $this->getEvents();
        $class = $this->className;

        if ($this->model->isNewRecord) {
            $this->data = (!$this->classDepDropName) ? $this->getRootsData() : $this->getDepDropRootsData();
        } else {
            $this->data = (!$this->classDepDropName) ? $this->getListData() : $this->getDepDropListData();
        }

        parent::init();
        $this->AssetBundle();
    }

    public function run()
    {

    }

    public function AssetBundle()
    {
        $view = $this->getView();
        Assets::register($view);
    }

    private function getRootsData()
    {
        $class = $this->className;

        $data = ($this->isNested)
            ? ArrayHelper::map($class::find()->select(['id', 'title'])->orderBy('title ASC')->roots()->all(), 'id',
                'title') : ArrayHelper::map($class::find()->select(['id', 'title'])->where(['pid' => null])
                ->orderBy('title ASC')->all(), 'id', 'title');

        return $data;
    }

    private function getDepDropRootsData()
    {
        $class = $this->className;
        $data = ArrayHelper::map($class::find()->select(['id', 'title'])->orderBy('title ASC')->all(), 'id', 'title');

        return $data;
    }

    private function getListData()
    {
        $class = $this->className;
        $data = '';

        if ($this->isNested) {

            $model = ($this->options['multiple']) ? $class::findOne(['id' => (int)$this->model->idProductCategories[0]])
                : $class::findOne(['id' => (int)$this->model->idProductCategories]);

            if (!$model || $model->isRoot()) {
                return $this->getRootsData();
            }

            $parent = $model->parents(1)->one();
            $list = $parent->children()->orderBy('title')->all();
            $data = ArrayHelper::map($list, 'id', 'title');
        } else {
            $model = ($this->options['multiple']) ? $class::findOne(['id' => (int)$this->model->idCategories[0]])
                : $class::findOne(['id' => (int)$this->model->idCategories]);
            if (!$model) {
                return $this->getRootsData();
            }
            $parent = $class::findOne(['id' => $model->pid]);
            $model = $class::find()->where(['pid' => $parent->id])->orderBy('title ASC')->all();
            $data = ArrayHelper::map($model, 'id', 'title');
        }

        return $data;
    }

    private function getDepDropListData()
    {

        $class = $this->classDepDropName;
        $data = ArrayHelper::map($class::find()->select(['id', 'title'])
            ->where(['idRegion' => $this->model->city->region->id])->orderBy('title ASC')->all(), 'id', 'title');

        return $data;
    }

    private function getEvents()
    {
        $event = [
            "change" => 'function() {
                getEventChange($(this), "' . $this->url . '");
            }', //            "select2:select" => "function() { alert('sel'); }",
            //            "select2:close" => "function() { alert('close'); }",
        ];
        return $event;
    }
}
