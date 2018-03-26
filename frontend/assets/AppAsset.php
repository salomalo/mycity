<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    //public $css = ['css/style2.css', 'css/style.css'];
    public $css = [
                  'css/font-awesome.min.css', 
                  'css/style.css',
                  'css/jquery.fancybox.css?v=2.1.5'
                  ];
    public $js = [
                  'js/jquery.ad-gallery.js', 
                  'js/modal.js', 
                  'js/jquery.mousewheel-3.0.6.pack.js', 
                  'js/jquery.fancybox.js?v=2.1.5',
                  'js/scripts.js',
//                  'js/addCustomfieldValue.js',
                ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'kartik\widgets\AssetBundle',
        'yii\web\JqueryAsset',
//        'yii\bootstrap\BootstrapPluginAsset',
//        'backend\assets\MixinsAsset',
        'frontend\assets\AdminLTEAsset'
    ];
    
//    public function init()
//    {
//        parent::init();
//        // resetting BootstrapAsset to not load own css files
//        \Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
//            'css' => []
//        ];
//    }
}

class MixinsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/twbs/bootstrap/less';
    public $css = [
        'mixins.less',
    ];
}