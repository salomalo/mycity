<?php

namespace frontend\extensions\City;

use yii\web\AssetBundle;

/**
 * Description of Assets
 *
 * @author dima
 */
class Assets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];

    public $sourcePath = '@frontend/extensions/City';

    public $js = ['js/city.js'];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
