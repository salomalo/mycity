<?php

namespace common\extensions;

use kartik\widgets\Select2;
use common\models\ProductCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\AssetBundle as AssetBundle; 

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of nestedSelect
 *
 * @author dima
 */

class MyselectAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}


class nestedSelect extends Select2
{
    //put your code here
    
    public $data;
    public $list;
    public $owner;
    public $options = [];
    private $_hidden = false;
    
    public function init()
    {
        //parent::init();
        $this->pluginOptions['theme'] = $this->theme;
        if (empty($this->pluginOptions['width'])) {
            $this->pluginOptions['width'] = '100%';
        }
        
        $roots = ProductCategory::find()->orderBy('title ASC')->roots()->all();

        foreach ($roots as $a)
        {
            if($a->isRoot()){
               $this->list[] = ['id'=>$a->id, 'title'=>$a->title];
               if (empty($this->options['onlyroots'])){
                  $this->getPodCat($a->id);
               }
            }

        }

        if(is_array($this->list)){
            $this->data =  ArrayHelper::map($this->list,'id','title');
        }
        else  $this->data = [];
        
        $this->initPlaceholder();
        
        Html::addCssClass($this->options, 'form-control');
        $this->initLanguage('language', true);
        $this->registerAssets();
        $this->renderInput();
    }
    
    public function getPodCat($id){
        $arr = ProductCategory::findOne($id)->children()->orderBy('title ASC')->all();
        
        foreach ($arr as $a)
        {
            $l='';
            for ($i=0; $i< $a->depth; $i++){
                $l.='-';
            }
            
            $this->list[]= ['id'=>$a->id, 'title'=>$l.$a->title];
            
        }
    }
    
    public function registerAssets()
    {
         parent::registerAssets();
    
         //$scripts = 'function format(state) {return "!!!" + state.title; }';
         $level = '<div style="width:20px"></div>';
         $scripts = "$('#productCategory1').select2({ formatResult: format,  formatSelection: format }); "; 
         $scripts .= "function format(item) {  console.log (item); return ( '<span class=`level'+item.level+'`>'+item.text+'</span>');}";
    
         $view = $this->getView();
        $view->registerJs($scripts);
        // MyselectAsset::register($view);
         
    }
    
}
