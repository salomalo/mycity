<?php

namespace backend\extensions\MyDatePicker;

use yii\web\AssetBundle;

class MyDatePickerLangUkAssets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];

    public $sourcePath = '@backend/extensions/MyDatePicker';

    public $js = ['js/datepicker-uk.js'];

    public $depends = ['backend\assets\JQueryUIAssets'];
}