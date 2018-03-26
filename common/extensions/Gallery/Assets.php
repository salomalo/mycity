<?php

namespace common\extensions\Gallery;

use yii\web\AssetBundle;

class Assets extends AssetBundle{
    
    public $sourcePath = "@common/extensions/Gallery/";

    public $js = [
           'js/script.js'     
    ];
    public $depends = [
        'yii\web\YiiAsset',
        
    ];
    public function init()
    {
        parent::init();
    }
} 

