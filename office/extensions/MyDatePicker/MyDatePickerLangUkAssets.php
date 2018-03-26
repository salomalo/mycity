<?php

namespace office\extensions\MyDatePicker;

use yii\web\AssetBundle;

class MyDatePickerLangUkAssets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];

    public $sourcePath = '@office/extensions/MyDatePicker';

    public $js = ['js/datepicker-uk.js'];

    public $depends = ['office\assets\JQueryUIAssets'];
}