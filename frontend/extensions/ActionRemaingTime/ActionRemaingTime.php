<?php

namespace frontend\extensions\ActionRemaingTime;

use yii\base\Widget;

class ActionRemaingTime extends Widget
{
    public $model;
    public $template = 'index';

    public function run()
    {
        return $this->render($this->template);
    }
}