<?php
namespace common\extensions\BusinessCustomField;
use yii\web\AssetBundle;

/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 15.07.16
 * Time: 12:51
 */
class CustomFieldListAssets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];

    public $sourcePath = '@common/extensions/BusinessCustomField';

    public $css = ['css/list/custom_field_list.css'];

    public $depends = ['yii\web\YiiAsset', 'yii\web\JqueryAsset', 'kartik\select2\Select2Asset', 'yii\bootstrap\BootstrapAsset'];
}