<?php

namespace office\extensions\BusinessFormWidget;

use yii\web\AssetBundle;
use common\models\Lang;

class Assets extends AssetBundle{
    public $depends = ['yii\web\YiiAsset'];
    
    public $publishOptions = ['forceCopy' => true];
    public function init()
    {
        $this->sourcePath = "@office/extensions/BusinessFormWidget/assets/";
        $this->js = [
                'https://maps.googleapis.com/maps/api/js?key=AIzaSyDE8LkS3r9zCmdgGNCOAs1z_yYIj5d_btQ&v=3.exp&language=' . Lang::$current->url,
                'https://cdn.rawgit.com/googlemaps/v3-utility-library/master/infobox/src/infobox.js',
                'js/device.js',
                'js/script.js',
                'js/markerclusterer.js',
        ];
        parent::init();
    }
} 

