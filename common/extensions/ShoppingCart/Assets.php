<?php

namespace common\extensions\ShoppingCart;

use yii\web\AssetBundle;


/**
 * Description of Assets
 *
 * @author dima
 */
class Assets extends AssetBundle{
    
    public $sourcePath = '@common/extensions/ShoppingCart';
    
    public $css = [
        
    ];

    public $js = [

    ];

    public $depends = [
        'yii\web\JqueryAsset',
        //'yii\bootstrap\BootstrapPluginAsset',
    ];
}