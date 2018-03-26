<?php

namespace frontend\extensions\FilterProduct;

use yii\web\AssetBundle;

/**
 * Description of Assets
 *
 * @author dima
 */
class Assets extends AssetBundle
{
    public $sourcePath = "@frontend/extensions/FilterProduct";

    public $css = ['css/ion.rangeSlider.css', 'css/ion.rangeSlider.skinModern.css'];
    
    public $js = ['js/ion.rangeSlider.js'];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    
    public function init()
    {
        parent::init();
        // resetting BootstrapAsset to not load own css files
//        \Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
//            'css' => []
//        ];
    }
}
