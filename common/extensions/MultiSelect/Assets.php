<?php

namespace common\extensions\MultiSelect;

use yii\web\AssetBundle;

/**
 * Description of Assets
 *
 * @author lexa
 */
class Assets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];
    public $sourcePath = '@common/extensions/MultiSelect';

    public $css = ['css/style.css'];
    public $js = ['js/scripts.js'];
    public $depends = ['yii\web\JqueryAsset'];
}
