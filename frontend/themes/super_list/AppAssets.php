<?php
namespace frontend\themes\super_list;

use yii\web\AssetBundle;

class AppAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/font-awesome.min.css',
        'css/simple-line-icons.css',
        'css/jquery.fancybox.css?v=2.1.5',
        'css/new/owl.carousel.css',
        'css/new/superlist-mint.css',
        'css/new/shop-style.css',
        'css/new/style.css',
        'css/new/blue-theme.css',
        'css/new/bootstrap-select.min.css',
        'css/new/mapescape.css',
    ];

    public $js = [
        'js/jquery.ad-gallery.js',
        'js/modal.js',
        'js/jquery.mousewheel-3.0.6.pack.js',
        'js/jquery.fancybox.js?v=2.1.5',
        'js/new/scripts.js',
        //'js/new/shopping-cart.js',
        'js/new/jquery.colorbox-min.js',
        'js/new/jquery.scrollTo.min.js',
        'js/new/owl.carousel.min.js',
        'js/new/bootstrap-select.min.js',
        'js/new/superlist.js',
        'js/new/inventor-google-map.js',
        'js/new/basket-ajax.js',
        'js/new/startuply.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'kartik\widgets\AssetBundle',
        'yii\web\JqueryAsset',
    ];
}
