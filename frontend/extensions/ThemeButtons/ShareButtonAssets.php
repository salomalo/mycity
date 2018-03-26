<?php

namespace frontend\extensions\ThemeButtons;

use yii\web\AssetBundle;
use yii\web\View;

class ShareButtonAssets extends AssetBundle
{
    public $jsOptions = ['position' => View::POS_HEAD];

    public $js = ['//vk.com/js/api/share.js?94'];
}
