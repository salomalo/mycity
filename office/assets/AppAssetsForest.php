<?php
namespace office\assets;

use yii\web\AssetBundle;

class AppAssetsForest extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
//        'css/font-awesome.min.css',
        'css/forest/custom-animations.css',
        'css/forest/lib/font-awesome.min.css',
//        'css/forest/style.css',
        'css/forest/style-package.css',
    ];

    public $js = [
        'js/forest/jquery.bxslider.min.js',
        'js/forest/jquery.mb.YTPlayer.min.js',
        'js/forest/jquery-ui-slider.min.js',
        'js/forest/modal-box.js',
        'js/forest/html5shiv.js',
        'js/forest/respond.min.js',
        'js/forest/jquery.flexslider-min.js',
        'js/forest/jquery.appear.js',
        'js/forest/jquery.plugin.js',
        'js/forest/jquery.countdown.js',
        'js/forest/jquery.waypoints.min.js',
        'js/forest/jquery.validate.min.js',
        'js/forest/toastr.min.js',
        'js/forest/startuply.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'kartik\widgets\AssetBundle',
        'yii\web\JqueryAsset',
    ];
}
