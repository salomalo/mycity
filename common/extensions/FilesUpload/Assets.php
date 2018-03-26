<?php

namespace common\extensions\FilesUpload;

use yii\web\AssetBundle;

class Assets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];
    public $sourcePath = '@common/extensions/FilesUpload';
    public $css = ['css/style.css'];
    public $js = ['js/script.js'];
    public $depends = ['yii\web\JqueryAsset'];
}
