<?php

namespace m\extensions\MultiSelect;

use yii\web\AssetBundle;

/**
 * Description of Assets
 *
 * @author lexa
 */
class Assets extends AssetBundle{
    
    public $sourcePath = '@m/extensions/MultiSelect';
    
    public $css = [
        'css/style.css',        
    ];

    public $js = [
        'js/scripts.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        //'yii\bootstrap\BootstrapPluginAsset',
    ];
}