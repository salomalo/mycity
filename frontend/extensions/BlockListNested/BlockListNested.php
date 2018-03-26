<?php
/**
 * Created by PhpStorm.
 * User: nautilus
 * Date: 6/15/14
 * Time: 4:16 PM
 */

namespace frontend\extensions\BlockListNested;
use yii;
use yii\base\Widget;
use yii\helpers\Url;

class BlockListNested extends Widget
{
    public $title;
    public $template ="index";
    public $className;
    public $attribute;
    public $path = 'business/index';
    public $p = false;
    public $id_category = null;

    public function init()
    {
        if (Yii::$app->controller->action->id === 'map') {
            $this->path = 'business/map';
        }
        parent::init();
    }

    public function run()
    {
        /* @var $models \yii\db\ActiveRecord[] */
        /* @var $class \yii\db\ActiveRecord */
        $class = $this->className;
        $this->p = $this->id_category ? true : false;

        if($this->p){     // $pid != null,
            $category = $class::find()->where(['id' => $this->id_category])->one();     // категория
            $models = $this->getChildrens($category);  // получаем детей
            if(!$models) {
                if($category->isRoot()){    // нет детей и категория корень, выводим все корни
                    $models = $this->getRoots($class);
                } else {   // нет детей и категория не корень, берём родителя и получаем детей
                    $root = $category->parents(1)->one();
                    $models = $this->getChildrens($root);
                }
            }
        } else { // все предприятия, просмотр предприятия
            if($this->id_category){ // для view
                $category = $class::find()->where(['id' => $this->id_category])->one();
                $root = $category->parents(1)->one();
                $models = ($root)? $this->getChildrens($root) : $this->getRoots($class);
            } else {
                $models = $this->getRoots($class);
            }
        }
        return $this->render($this->template, [
            'models' => $models,
            'rootCat' => $this->p,
        ]);
    }

    public function createUrl($param = null)
    {
        if($param){
           return Url::to([$this->path,$this->attribute => $param]);
        }
        else{
            return Url::to([$this->path], true); 
        }
    }
    
    private function getRoots($class){
        return $class::find()->where('id <> :id', [':id' => 6645])->roots()->all();
    }
    
    private function getChildrens($root){
        return $root->children(1)->all();
    }
} 