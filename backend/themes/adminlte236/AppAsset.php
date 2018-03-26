<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\themes\adminlte236;

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
        'css/main.css',
//        'css/sb-admin.css',
//        "/adminlte236/bootstrap/css/bootstrap.min.css",
        "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css",
        "https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css",

        "/adminlte236/dist/css/AdminLTE.min.css",
        "/adminlte236/dist/css/skins/_all-skins.min.css",
//        "/adminlte236/plugins/iCheck/flat/blue.css",
//        "/adminlte236/plugins/morris/morris.css",
//        "/adminlte236/plugins/jvectormap/jquery-jvectormap-1.2.2.css",
//        "/adminlte236/plugins/datepicker/datepicker3.css",
//        "/adminlte236/plugins/daterangepicker/daterangepicker.css",
//        "/adminlte236/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css",
        'lte/css/fixAdminLTE.css',
    ];
    public $js = [
        'js/addCustomfieldValue.js',
        'js/scripts.js',
        'js/add-time.js',
        'js/edit-product.js',
        'js/edit-ads.js',
        'js/delBusinessList.js',
        '/adminlte236/jquery-ui/jquery.slimscroll.min.js',

//        "/adminlte236/bootstrap/js/bootstrap.min.js",
//        "https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js",
//        "/adminlte236/plugins/sparkline/jquery.sparkline.min.js",
//        "/adminlte236/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js",
//        "/adminlte236/plugins/jvectormap/jquery-jvectormap-world-mill-en.js",
//        "/adminlte236/plugins/knob/jquery.knob.js",
//        "https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js",
//        "/adminlte236/plugins/daterangepicker/daterangepicker.js",
//        "/adminlte236/plugins/datepicker/bootstrap-datepicker.js",
//        "/adminlte236/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js",
//        "/adminlte236/plugins/slimScroll/jquery.slimscroll.min.js",
        "/adminlte236/plugins/fastclick/fastclick.js",
        "/adminlte236/dist/js/app.min.js",
        "/adminlte236/dist/js/demo.js",
        ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
        'backend\assets\JQueryUIAssets',
    ];
}
