<?php
namespace frontend\extensions\CityPopup;

use yii\base\Widget;

class CityPopup extends Widget
{
    public function init()
    {
        CityPopupAssets::register($this->view);
    }
    
    public function run()
    {
        return $this->render('main');
    }
}
