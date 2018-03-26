<?php

namespace frontend\themes;

use yii\bootstrap\BootstrapAsset;

class ShopAsset extends \yii\web\AssetBundle
{
    public $css = [
        'css/shop.css',
        'css/font-awesome.min.css',
        'css/basket-default.css',
    ];

    public $js = [
        'js/shop.js',
        'js/modal.js',
        'js/new/basket-ajax.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\authclient\widgets\AuthChoiceAsset',
    ];
}