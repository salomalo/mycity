<?php

namespace common\extensions\NestedSelectCategory;

use yii\web\AssetBundle;

/**
 * Description of Assets
 *
 * @author lexa
 */
class Assets extends AssetBundle{
    
    public $sourcePath = '@common/extensions/NestedSelectCategory';
    
    public $css = [
        
    ];

    public $js = [
         'js/select2.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        //'yii\bootstrap\BootstrapPluginAsset',
    ];
}