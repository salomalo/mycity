<?php

namespace frontend\extensions\BusinessAds;

/**
 * Description of BusinessAds
 *
 * @author dima
 */
class BusinessAds extends \yii\base\Widget
{
    public $model = null;
    
    public function run() {
        
        if(!$this->model || !$this->model->ads){
            return;
        }
        
        return $this->render('index', [
            'models' => $this->model->ads,
            'alias' => $this->model->url
        ]);
    }
}
