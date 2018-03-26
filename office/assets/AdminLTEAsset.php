<?php
namespace office\assets;

use yii\web\AssetBundle;

class AdminLTEAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/AdminLTE/modules/css/font-awesome.min.css',
        '/AdminLTE/modules/css/ionicons.min.css',
        '/AdminLTE/css/AdminLTE.min.css',
        '/AdminLTE/css/skins/_all-skins.min.css',
        '/AdminLTE/css/style.css',
    ];
    public $js = [
        '/AdminLTE/modules/jquery-ui/jquery.slimscroll.min.js',
        '/AdminLTE/modules/fastclick.js',
        '/AdminLTE/js/app.min.js',
        '/js/add-time.js',
        '/js/edit-product.js',
        '/js/edit-ads.js',
        '/js/addCustomfieldValue.js',
        '/js/scripts.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'office\assets\JQueryUIAssets',
    ];
}
