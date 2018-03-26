<?php

namespace office\extensions\MyDatePicker;

use yii\web\AssetBundle;

class MyDatePickerAssets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];

    public $sourcePath = '@office/extensions/MyDatePicker';

    public $js = ['js/datepicker_init.js'];

    public $depends = ['yii\web\JqueryAsset', 'office\assets\JQueryUIAssets'];
}