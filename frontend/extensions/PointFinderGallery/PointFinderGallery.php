<?php
namespace frontend\extensions\PointFinderGallery;

use yii\base\Widget;

class PointFinderGallery extends Widget
{
    public $data;
    
    public function init()
    {
        PointFinderGalleryAssets::register($this->view);
    }
    
    public function run()
    {
        return $this->data ? $this->render('owl', ['elements' => $this->data]) : null;
    }
}
