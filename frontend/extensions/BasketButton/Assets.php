<?php

namespace frontend\extensions\BasketButton;

use yii\web\AssetBundle;

/**
 * Description of Assets
 *
 * @author dima
 */
class Assets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];

    public $sourcePath = '@frontend/extensions/BasketButton';

    public $js = ['js/script.js'];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
