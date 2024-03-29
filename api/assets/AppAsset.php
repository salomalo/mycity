<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace api\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    //public $css = ['css/style.css'];
    //public $js = ['js/jquery.ad-gallery.js'];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
//        'backend\assets\MixinsAsset',
    ];
}

class MixinsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/twbs/bootstrap/less';
    public $css = [
        'mixins.less',
    ];
}