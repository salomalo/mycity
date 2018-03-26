<?php

namespace office\extensions\MyDatePicker;

use yii\web\AssetBundle;

class MyDatePickerLangRuAssets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];

    public $sourcePath = '@office/extensions/MyDatePicker';

    public $js = ['js/datepicker-ru.js'];

    public $depends = ['office\assets\JQueryUIAssets'];
}