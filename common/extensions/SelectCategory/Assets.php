<?php

namespace common\extensions\SelectCategory;

use yii\web\AssetBundle;

/**
 * Description of Assets
 *
 * @author lexa
 */
class Assets extends AssetBundle{
    
    public $sourcePath = '@common/extensions/SelectCategory';
    
    public $css = [
        
    ];

    public $js = [

    ];

    public $depends = [
        'yii\web\JqueryAsset',
        //'yii\bootstrap\BootstrapPluginAsset',
    ];
}