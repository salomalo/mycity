<?php
namespace frontend\extensions\PointFinderGallery;

use yii\web\AssetBundle;

class PointFinderGalleryAssets extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];

    public $sourcePath = '@frontend/extensions/PointFinderGallery';

    public $js = ['js/point_finder_gallery.js', 'js/owl.carousel.min.js'];

    public $css = [
        'https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&subset=cyrillic,cyrillic-ext,latin-ext',
        'css/owl.carousel.css',
        'css/owl.theme.css',
        'css/owl.transitions.css',
        'css/point_finder_gallery.css',
    ];

    public $depends = ['yii\web\JqueryAsset', 'frontend\themes\super_list\AppAssets'];
}
