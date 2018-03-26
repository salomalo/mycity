<?php
namespace backend\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/main.css',
        'css/sb-admin.css',
        'lte/css/bootstrap.min.css',
        'lte/css/font-awesome.min.css',
        'lte/css/jvectormap/jquery-jvectormap-1.2.2.css',
        'lte/css/daterangepicker/daterangepicker-bs3.css',
        'lte/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
        'lte/css/AdminLTE.css',
        'lte/css/fixAdminLTE.css',
    ];
    public $js = [
        'js/addCustomfieldValue.js',
        'js/scripts.js',
        'js/add-time.js',
        'js/edit-product.js',
        'js/edit-ads.js',
        'js/delBusinessList.js',
        'lte/js/jquery-ui-1.10.3.min.js',
        'lte/js/plugins/morris/morris.min.js',
        'lte/js/plugins/sparkline/jquery.sparkline.min.js',
        'lte/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
        'lte/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
        'lte/js/plugins/jqueryKnob/jquery.knob.js',
        'lte/js/plugins/daterangepicker/daterangepicker.js',
        'lte/js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js',
        'lte/js/plugins/iCheck/icheck.min.js',
        'lte/js/AdminLTE/app.js',
        'lte/js/AdminLTE/demo.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}
