<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AdminLTEAsset extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];
    
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = ['css/AdminLTE/font-awesome.min.css', 'css/AdminLTE/ionicons.min.css', 'css/AdminLTE/AdminLTE.min.css', 'css/AdminLTE/skins/_all-skins.min.css'];

//    public $js = ['js/jquery.ad-gallery.js'];

    public $depends = ['yii\web\YiiAsset', 'yii\web\JqueryAsset', 'yii\bootstrap\BootstrapAsset'];
}
