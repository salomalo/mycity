<?php

namespace backend\extensions\MyDatePicker;

use yii\web\AssetBundle;

class MyDatePickerLangRuAssets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];

    public $sourcePath = '@backend/extensions/MyDatePicker';

    public $js = ['js/datepicker-ru.js'];

    public $depends = ['backend\assets\JQueryUIAssets'];
}