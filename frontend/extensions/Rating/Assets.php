<?php

namespace frontend\extensions\Rating;

use yii\web\AssetBundle;

/**
 * Description of Assets
 *
 * @author dima
 */
class Assets extends AssetBundle{
    
    public $sourcePath = '@frontend/extensions/Rating';
    
    public $css = [
        
    ];

    public $js = [];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
