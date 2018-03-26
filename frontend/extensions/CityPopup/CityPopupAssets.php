<?php
namespace frontend\extensions\CityPopup;

use yii\web\AssetBundle;

class CityPopupAssets extends AssetBundle
{
    public $sourcePath = "@frontend/extensions/CityPopup";

    public $publishOptions = ['forceCopy' => true];

    public $js = ['js/city_popup.js'];
    public $css = ['css/city_popup.css'];

    public $depends = ['yii\web\JqueryAsset'];
}
