<?php
namespace office\assets;

use yii\web\AssetBundle;

class JQueryUIAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/AdminLTE/modules/jquery-ui/jquery-ui.min.css',
        '/AdminLTE/modules/jquery-ui/jquery-ui.structure.min.css',
        '/AdminLTE/modules/jquery-ui/jquery-ui.theme.min.css',
    ];
    public $js = ['/AdminLTE/modules/jquery-ui/jquery-ui.min.js'];
    public $depends = ['yii\web\JqueryAsset'];
}
