<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace office\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css',
        'css/bootstrap.min.css',
        'css/font-awesome.min.css',
        'css/jvectormap/jquery-jvectormap-1.2.2.css',
        'css/daterangepicker/daterangepicker-bs3.css',
        'css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
        'css/AdminLTE.css',
        'css/modal.css',
    ];
    public $js = [
        'js/jquery-ui-1.10.3.min.js',
        'js/bootstrap.min.js',
        'js/plugins/morris/morris.min.js',
        'js/plugins/sparkline/jquery.sparkline.min.js',
        'js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
        'js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
        'js/plugins/jqueryKnob/jquery.knob.js',
        'js/plugins/daterangepicker/daterangepicker.js',
        'js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js',
        'js/plugins/iCheck/icheck.min.js',
        'js/AdminLTE/app.js',
        //'js/AdminLTE/demo.js',
        'js/add-time.js', 
        'js/edit-product.js', 
        'js/edit-ads.js',
        'js/addCustomfieldValue.js',
        'js/scripts.js',
        'js/modal.js',
        'js/icheck.min.js',
                
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}

class MixinsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/twbs/bootstrap/less';
    public $css = [
        'mixins.less',
    ];
}