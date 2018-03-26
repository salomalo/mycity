<?php
namespace common\extensions\BusinessCustomField;

use yii\web\AssetBundle;

class EchoCustomFieldAssets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];

    public $sourcePath = '@common/extensions/BusinessCustomField';

    public $js = ['js/echo/load_custom_field.js'];

    public $depends = ['yii\web\YiiAsset', 'yii\web\JqueryAsset', 'kartik\select2\Select2Asset', 'yii\bootstrap\BootstrapAsset'];
}
