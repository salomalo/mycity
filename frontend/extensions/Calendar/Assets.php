<?php

namespace frontend\extensions\Calendar;

use yii\web\AssetBundle;

/**
 * Description of Assets
 *
 * @author dima
 */
class Assets extends AssetBundle{
    
    public $sourcePath = "@frontend/extensions/Calendar";

    public $js = [
//        'js/scripts.js',
        'js/calendar.js',
        ];
    public $css = [
        'css/style.css',
        ];
    
    public $depends = [
        'yii\web\YiiAsset',
    ];
    public function init()
    {
        parent::init();
    }
} 