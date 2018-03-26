<?php

namespace common\extensions\FilesUpload;

use yii\base\Widget;

class FilesUpload extends Widget
{
    public $model;

    public function run()
    {
        Assets::register($this->getView());
        return $this->render('index', ['model' => $this->model]);
    }
}