<?php

namespace frontend\extensions\FotoInPost;

use yii\web\AssetBundle;


/**
 * Description of Assets
 *
 * @author dima
 */
class Assets extends AssetBundle
{
    public $sourcePath = '@frontend/extensions/FotoInPost';
    
    public $css = [
        'css/amazingslider-1.css'
    ];

    public $js = [
        'js/amazingslider.js',
        'js/initslider-1.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
