<?php

namespace frontend\extensions\Share42;

class Share42 extends \yii\base\Widget
{
    public $photo = null;

    public function run()
    {
        $view = $this->getView();
        Assets::register($view);

        return $this->render('index', ['photo' => $this->photo]);
    }
}
