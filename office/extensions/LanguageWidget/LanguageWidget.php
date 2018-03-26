<?php
namespace office\extensions\LanguageWidget;


class LanguageWidget extends \yii\base\Widget
{
    public function run()
    {
        return $this->render('index');
    }
}