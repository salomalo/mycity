<?php

namespace backend\extensions\MyDatePicker;

use yii\web\AssetBundle;

class MyDatePickerAssets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];

    public $sourcePath = '@backend/extensions/MyDatePicker';

    public $js = ['js/datepicker_init.js'];

    public $depends = ['yii\web\JqueryAsset', 'backend\assets\JQueryUIAssets'];
}