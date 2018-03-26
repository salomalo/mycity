<?php

namespace common\extensions\MultiSelect2;

use yii\web\AssetBundle;

/**
 * Description of Assets
 *
 * @author lexa
 */
class Assets extends AssetBundle{
    
    public $sourcePath = '@common/extensions/MultiSelect2';
    
    public $publishOptions = [
        'forceCopy' => true,
    ];
    
    public $css = [
       
    ];

    public $js = [
        'js/multiSelect2Scripts.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        //'yii\bootstrap\BootstrapPluginAsset',
    ];
}