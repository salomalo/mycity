<?php
namespace frontend\themes\kuteshop;

use yii\web\AssetBundle;

class AppAssets extends AssetBundle
{
    public $sourcePath = '@frontend/themes/kuteshop/assets';

    public $css = [
        'css/style.css',
        'css/comment.css'
    ];

    public $js = [
        //'js/jquery.min.js',
        'js/jquery.sticky.js',
        'js/owl.carousel.min.js',
        //'js/bootstrap.min.js',
        'js/jquery.countdown.min.js',
        'js/jquery.bxslider.min.js',
        'js/jquery.actual.min.js',
        'js/jquery-ui.min.js',
        'js/chosen.jquery.min.js',
        'js/jquery.parallax-1.1.3.js',
        'js/jquery.elevateZoom.min.js',
        'js/fancybox/source/jquery.fancybox.pack.js',
        'js/fancybox/source/helpers/jquery.fancybox-media.js',
        'js/fancybox/source/helpers/jquery.fancybox-thumbs.js',
        'js/arcticmodal/jquery.arcticmodal.js',
        'js/main.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'kartik\widgets\AssetBundle',
        'yii\web\JqueryAsset',
    ];
}
