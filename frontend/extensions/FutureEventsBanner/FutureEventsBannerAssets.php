<?php

namespace frontend\extensions\FutureEventsBanner;

use yii\web\AssetBundle;


/**
 * Description of Assets
 *
 * @author dima
 */
class FutureEventsBannerAssets extends AssetBundle
{
    public $sourcePath = '@frontend/extensions/FutureEventsBanner';

    public $css = ['css/fut_event_banner.css'];
    public $js = ['js/fut_event_banner.js'];
    public $depends = ['yii\web\JqueryAsset'];
}