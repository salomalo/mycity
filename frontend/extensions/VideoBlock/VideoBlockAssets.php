<?php

namespace frontend\extensions\VideoBlock;

use yii\web\AssetBundle;


/**
 * Description of Assets
 *
 * @author dima
 */
class VideoBlockAssets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];
    public $sourcePath = '@frontend/extensions/VideoBlock';

    public $js = [
        'js/jquery.mb.YTPlayer.min.js',
        'js/select2.min.js',
        'js/video-block.js',
    ];

    public $css = [
        'css/jquery.mb.YTPlayer.min.css',
        'css/select2.css',
        'css/golden-forms.min.css',
        'css/style.css',
        'css/video-block.css',
    ];

    public $depends = ['yii\web\JqueryAsset'];
}
