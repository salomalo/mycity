<?php
namespace common\extensions\BusinessCustomField;

use yii\web\AssetBundle;

class CustomFieldFilterAssets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];

    public $sourcePath = '@common/extensions/BusinessCustomField';

    public $css = ['css/filter/custom_field_filter.css'];
    public $js = ['js/filter/custom_field_filter.js'];

    public $depends = ['yii\web\YiiAsset', 'yii\web\JqueryAsset', 'kartik\select2\Select2Asset', 'yii\bootstrap\BootstrapAsset'];
}
