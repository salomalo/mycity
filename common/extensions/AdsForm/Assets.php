<?php

namespace common\extensions\AdsForm;

use yii\web\AssetBundle;

class Assets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];
    public $sourcePath = '@common/extensions/AdsForm';

    public $js = [
        'js/bootstrap-select.min.js',
        'js/script.js',
    ];

    public $depends = ['yii\web\JqueryAsset'];
}
