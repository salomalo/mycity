<?php

namespace frontend\extensions\Share42;

use yii\web\AssetBundle;

/**
 * Description of Assets
 *
 * @author dima
 */
class Assets extends AssetBundle
{
    public $sourcePath = "@frontend/extensions/Share42";
    
    public $js = ['js/share42.js'];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    
    public function init()
    {
        parent::init();
    }
}
