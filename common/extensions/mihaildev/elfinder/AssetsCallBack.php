<?php
/**
 * Date: 23.01.14
 * Time: 0:51
 */

namespace common\extensions\mihaildev\elfinder;


use yii\web\AssetBundle;

class AssetsCallBack extends AssetBundle{
    public $js = array(
        'js/elfinder.callback.js'
    );
    public $depends = array(
        'yii\web\JqueryAsset'
    );

    public function init()
    {
        $this->sourcePath = __DIR__."/assets";
        parent::init();
    }
} 