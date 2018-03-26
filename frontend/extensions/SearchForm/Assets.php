<?php
namespace frontend\extensions\SearchForm;

use yii\web\AssetBundle;

class Assets extends AssetBundle
{
    public $sourcePath = '@frontend/extensions/SearchForm';
    public $js = ['js/SearchForm.js'];
    public $depends = ['yii\web\JqueryAsset',];
    public $publishOptions = ['forceCopy' => true];
}