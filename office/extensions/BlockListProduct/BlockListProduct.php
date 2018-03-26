<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace office\extensions\BlockListProduct;
use Yii;
use common\models\ProductCategory;
use yii\helpers\Url;

class BlockListProduct extends \yii\base\Widget
{
    public $title;
    public $className;
    public $attribute;
    public $path = 'product/index';

    public function run()
    {
        /**@var $models \yii\db\ActiveRecord[] */
        /**@var $class \yii\db\ActiveRecord */
        $class = $this->className;
        $models = [];  
        
        $value = \Yii::$app->request->getQueryParam($this->attribute);
        
        if(!$value){ 
           $models = $this->getRootCategorys($class); 
        }
        else{
            $arr = $class::find()->where(['id'=>$value])->one();
          
            if($arr){ 
                    $descendants = $arr->children()->all();
 
                    if($descendants){ // есть Children
                               $models = $this->getAllChildren($descendants); 
                    }
                    else{ // нет Children
                            $category = $class::find()->where(['id'=>$value])->one();
                            $parent = $category->parent()->one();
                             //echo \yii\helpers\BaseVarDumper::dump($parent, 10, true); die();
                            if($parent){
                                    $arr = $class::find()->where(['id'=>$parent['id']])->one();
                                    if($arr){
                                        $descendants = $arr->children()->all();
                                        $models = $this->getAllChildren($descendants); 
                                    }
                            }
                            else{ // root пустая категория
                               $models = $this->getRootCategorys($class); 
                            }
                        }
            }
            else{
                 Yii::$app->getResponse()->redirect(['product/index']);
            }
            
        }
       
        return $this->render('index', [
            'models' =>$models,
        ]);
    }
    
    protected function getRootCategorys($class){
                $roots = $class::find()->addOrderBy('lft')->all();
                foreach ($roots as $item)
                {
                    if($item->isRoot()){
                       $models[] = ['id'=>$item->id, 'title'=>$item->title, 'level' => $item->level]; 
                    }
                }
                return $models;
    }

        public function getAllChildren($descendants){
        $models = [];
        foreach ($descendants as $item)
            {
                $models[] = ['id'=>$item->id, 'title'=>$item->title, 'level' => $item->level]; 
            }
        return $models;
    }

    public function createUrl($param)
    {
        return Url::to([$this->path, $this->attribute => $param]);
    }
}
