<?php
namespace frontend\extensions\VkGroup;

use yii\web\AssetBundle;
use yii\web\View;

class VkGroupAssets extends AssetBundle
{
//    public $publishOptions = ['forceCopy' => true];
    public $sourcePath = '@frontend/extensions/VkGroup';

    public $js = ['js/vk_banner_api.js'];

    public $jsOptions = ['position' => View::POS_BEGIN];
}
