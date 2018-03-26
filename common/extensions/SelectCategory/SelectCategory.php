<?php

namespace common\extensions\SelectCategory;

use Yii;
use yii\base\Widget;
use yii\web\View;
use yii\helpers\Json;
use common\extensions\SelectCategory\Assets;

class meSelectCategory extends Widget {
    
     public $data =[];   // ['id'=>id, 'title' => title] ....
     public $level =0;
     public $url;
     public $TitleEmptyOptions ="Выберите категорию";
     
    public function init()
    {     
        $this->AssetBundle();
        return parent::init();
    }
    
    public function run() {
        
          echo $this->render('index',[
              'data'=>$this->data,
              'TitleEmptyOptions'=>$this->TitleEmptyOptions
              ]); 
    }
    public function AssetBundle(){
        $JavaScript = $this->render('scripts.js',
            ['url'=>$this->url,
             'TitleEmptyOptions'=>$this->TitleEmptyOptions
            ]);
        $view = $this->getView();
        $view->registerJs($JavaScript, View::POS_END); 
        Assets::register($view);     
    }    
    
}