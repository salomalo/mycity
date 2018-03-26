<?php

namespace common\extensions\Actions;

use yii\web\AssetBundle;

/**
 * Description of Assets
 *
 * @author dima
 */
class Assets extends AssetBundle{
    
    public $sourcePath = '@common/extensions/Actions';
    
    public $css = [
        
    ];

    public $js = [
       'js/actions.js'   
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        //'yii\bootstrap\BootstrapPluginAsset',
    ];
}