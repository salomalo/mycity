<?php

namespace common\extensions\Comments;

use yii\web\AssetBundle;

/**
 * Description of Assets
 *
 * @author dima
 */
class Assets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];
    public $sourcePath = '@common/extensions/Comments';
    
    public $css = [];

    public $js = [
        'js/script.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
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
