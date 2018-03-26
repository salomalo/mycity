<?php
namespace common\extensions\BusinessCustomField;

use yii\base\Widget;

class EchoCustomField extends Widget
{
    public $model;

    public function init()
    {
        parent::init();
        EchoCustomFieldAssets::register($this->view);
    }

    public function run()
    {
        return $this->render('echo/main', ['model' => $this->model]);
    }
}