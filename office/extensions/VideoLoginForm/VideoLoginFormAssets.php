<?php
namespace office\extensions\VideoLoginForm;

use yii\web\AssetBundle;

class VideoLoginFormAssets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];
    public $sourcePath = '@office/extensions/VideoLoginForm';

    public $js = [
        'js/jquery.mb.YTPlayer.min.js',
        'js/video-login.js',
    ];

    public $css = [
        'css/jquery.mb.YTPlayer.min.css',
        'css/video-login.css',
    ];

    public $depends = ['yii\web\JqueryAsset'];
}
