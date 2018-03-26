<?php

namespace backend\extensions\City;

use yii\web\AssetBundle;

/**
 * Description of Assets
 *
 * @author dima
 */
class Assets extends AssetBundle{
    
    public $sourcePath = '@backend/extensions/City';
    
    public $css = [
        
    ];

    public $js = [
        'js/city.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
