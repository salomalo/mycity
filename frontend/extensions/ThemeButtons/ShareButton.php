<?php

namespace frontend\extensions\ThemeButtons;

use yii\base\Widget;

class ShareButton extends Widget
{
    public $title;

    public function run()
    {
        return $this->render('share/main', ['title' => $this->title]);
    }
}
