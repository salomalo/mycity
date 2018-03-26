<?php

namespace frontend\extensions\SearchFormNew;

use yii\web\AssetBundle;

class Assets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];
    public $sourcePath = '@frontend/extensions/SearchForm';
    public $js = ['js/SearchForm.js'];
    public $depends = ['yii\web\JqueryAsset'];
}
