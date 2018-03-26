<?php
namespace office\extensions\SupportChat;

use yii\web\AssetBundle;

class SupportChatAssets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];

    public $sourcePath = '@office/extensions/SupportChat';

    public $js = ['js/support.js'];
    public $css = ['css/support.css'];

    public $depends = ['office\assets\JQueryUIAssets', 'office\assets\AdminLTEAsset'];
}
