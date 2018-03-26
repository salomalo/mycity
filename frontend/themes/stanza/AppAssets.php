<?php
namespace frontend\themes\stanza;

use yii\web\AssetBundle;

class AppAssets extends AssetBundle
{
    public $sourcePath = '@frontend/themes/stanza/assets';

    public $css = [
        //'css/style.css',
        'css/default.css',
        'revolution/css/settings.css',
        'revolution/css/layers.css',
        'revolution/css/navigation.css',
        'css/mediaQueries.css',
    ];

    public $js = [
        'js/owl.carousel.min.js',
        'js/colorbox-min.js',
        'js/isotope.pkgd.min.js',
        'revolution/js/jquery.themepunch.tools.min.js',
        'revolution/js/jquery.themepunch.revolution.min.js',
        'revolution/js/extensions/revolution.extension.slideanims.min.js',
        'revolution/js/extensions/revolution.extension.layeranimation.min.js',
        'revolution/js/extensions/revolution.extension.navigation.min.js',
        'revolution/js/extensions/revolution.extension.actions.min.js',
        'revolution/js/extensions/revolution.extension.video.min.js',
        'js/buttons.js',
        'js/lightslider.min.js',
        'js/lightgallery.min.js',
        'js/jquery.matchHeight-min.js',
        'js/configuration.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'kartik\widgets\AssetBundle',
        'yii\web\JqueryAsset',
    ];
}
