<?php

namespace m\extensions\MultiSelect;

use Yii;
use yii\helpers\Html;
use yii\widgets\InputWidget;
use yii\web\View;
use yii\helpers\Json;
use m\extensions\MultiSelect\Assets;


class MultiSelect extends \kartik\base\InputWidget {
         
     // public $placeholder ="Выберите категорию";
      public  $url;
      public  $className;
      public  $multiple=true;
      public $search = true;
      // public  $pid;
      public function init() {     
      parent::init();
      $this->AssetBundle();
    }
    
    public function run() {
        if(isset($this->url)){
            /**@var ActiveRecord $class*/
            $class = $this->className;
            $attr = $this->attribute;
            $where = $this->model->$attr;
            $data=[];
            $flag = true;
            if (is_array($where)){
                foreach($where as $witem){
                    if (empty($witem)) {
                        $flag = false;
                    }
                }
            }
            if (!empty($where) && $flag) {
                $data = $class::find()->select(['id','title'])->where(['id'=>$where])->all();
            }
            
            echo $this->render('index',[
                'placeholder'=>isset($this->options['placeholder'])?$this->options['placeholder']:"Выберите что нить",
                'input' => $this->renderInput(),
                'data_url' => $this->url,
                'data' => $data,
                'search' => $this->search,
//                'pid' => $this->pid,
            ]); 
        } else {
            echo " Не задан параметр url";
        }  
    }
    
    public function AssetBundle() {
        $view = $this->getView();
        Assets::register($view);     
    }
    
    protected function renderInput() {
        $attr = $this->attribute;
        if ($this->multiple){
            $items = [];
            if (is_array($this->model->$attr)){
            foreach ($this->model->$attr as $item){
                $items[$item]= $item;
            }
            }
            $input = Html::activeDropDownList($this->model, $this->attribute,$items,['multiple'=>'multiple']);
        } else{
            $item = $this->model->$attr;
            $items[$item]= $item; 
            $input = Html::activeDropDownList($this->model, $this->attribute,$items,[]);
        }          
        return $input;
    }
    
}