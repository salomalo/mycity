<?php

namespace common\extensions\NestedSelectCategory;

use Yii;
use yii\base\Widget;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use common\extensions\NestedSelectCategory\Assets;


class NestedSelectCategory extends \yii\widgets\InputWidget {
    
//     public $data =[];   // ['id'=>id, 'title' => title] ....
     public $CategoryModel;
     public $url;
     public $changejs ='';
     public $multiple = false;
     public $Class_outer='nestedselectcategory';
     public $id='nestedselectcategory';
     public $TitleEmptyOptions ="Выберите категорию";
     
    public function init()
    {     
       
       parent::init();
        if($this->hasModel()){
            $this->name = $this->name ?: Html::getInputName($this->model, $this->attribute);
            $this->value = $this->value ?: Html::getAttributeValue($this->model, $this->attribute);
        }       
        $this->AssetBundle();
    }

    protected function renderInput()
    {

    }   
    
    
    
    public function run() {
       $active = false;
       $content = Html::beginTag('div',['class'=>$this->Class_outer,'data-name'=>$this->name]);
//       if($this->hasModel()){
//          $content .= Html::hiddenInput($this->name, $this->value, $this->options);
//       }
       $class = $this->CategoryModel;
        if ($this->value){
            $category = $class::findOne(['id'=>$this->value]);
            if ($category->isRoot()){
                $parents = [];
                $active = true;
            }else{
                $parents =  $category->parents()->all();  
            }
        }
        $i = 0; // указатель родительского ресурса
        $roots = $class::find()->roots()->all();
            $content .= $this->render('select',[                
              'select'=> (!empty($parents[$i]))?$parents[$i]->id:$this->value,
              'data'=>$roots,
              'active'=>$active,
              'name'=>$this->name,
              'id'=>$this->id,
              'TitleEmptyOptions'=>$this->TitleEmptyOptions
            ]); 
        if ($this->value) {
            $active = false;
            foreach ($parents as $item) {
                $i=$i+1;
                $children = $item->children(1)->all();
                if (end($parents)->id==$item->id) {$active =true;}
                $content .= $this->render('select',[                
                    'select'=>(end($parents) === $item)?$this->value:$parents[$i]->id,
                    'active'=>$active,
                    'id'=>$this->id,
                    'name'=>$this->name,
                    'data'=>$children,
                    'TitleEmptyOptions'=>$this->TitleEmptyOptions
                ]); 
            
            }
            $children = $category->children(1)->all();
            if (!empty($children)){
                $content .= $this->render('select',[                
                    'select'=>'',
                    'active'=>false,
                    'id'=>$this->id,
                    'name'=>$this->name,                    
                    'data'=>$children,
                    'TitleEmptyOptions'=>$this->TitleEmptyOptions
                ]);                 
            } 
        }    
        $content .= Html::endTag('div');    
        echo $content;
    }
    public function AssetBundle(){
        $url = $this->url !== null && is_array($this->url) ? Url::to($this->url) : '';
        $JavaScript="";
//        if ($this->changejs){
//            $JavaScript.=$this->changejs;
//        }
        $attr= ['url'=>$url,
                'id'=>$this->id,
                'name'=>$this->name,                    
                'TitleEmptyOptions'=>$this->TitleEmptyOptions,
                'Class_outer' => $this->Class_outer,            
                'changejs' => '',            
               ]; 
        if ($this->changejs){
            $attr['changejs']=$this->changejs;
        }
        
        
        $JavaScript.= $this->render('scripts.js',$attr);
        $view = $this->getView();
        $view->registerJs($JavaScript, View::POS_END); 
        Assets::register($view);     
    }    
    
}