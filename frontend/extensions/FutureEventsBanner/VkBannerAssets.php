<?php

namespace frontend\extensions\FutureEventsBanner;

use yii\web\AssetBundle;
use yii\web\View;


/**
 * Description of Assets
 *
 * @author dima
 */
class VkBannerAssets extends AssetBundle
{
//    public $publishOptions = ['forceCopy' => true];
    public $sourcePath = '@frontend/extensions/FutureEventsBanner';

    public $js = ['js/vk_banner_api.js'];
    public $css = ['css/vk_banner.css'];

    public $jsOptions = ['position' => View::POS_BEGIN];
}