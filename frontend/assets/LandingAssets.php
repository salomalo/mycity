<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class LandingAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        '//fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext',
        'css/font-awesome.min.css',
        'css/jquery.fancybox.css?v=2.1.5',
        'css/new/superlist-mint.css',
        'css/new/style.css',
    ];
    public $js = [
        'js/jquery.ad-gallery.js',
        'js/modal.js',
        'js/jquery.mousewheel-3.0.6.pack.js',
        'js/jquery.fancybox.js?v=2.1.5',
        'js/scripts.js',
//        'js/addCustomfieldValue.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'kartik\widgets\AssetBundle',
        'yii\web\JqueryAsset',
    ];
}
