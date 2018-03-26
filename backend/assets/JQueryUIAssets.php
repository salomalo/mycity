<?php
namespace backend\assets;

use yii\web\AssetBundle;

class JQueryUIAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/adminlte236/jquery-ui/jquery-ui.min.css',
        '/adminlte236/jquery-ui/jquery-ui.structure.min.css',
        '/adminlte236/jquery-ui/jquery-ui.theme.min.css',
    ];
    public $js = ['/adminlte236/jquery-ui/jquery-ui.min.js'];
    public $depends = ['yii\web\JqueryAsset'];
}
