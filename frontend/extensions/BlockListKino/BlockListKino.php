<?php

namespace frontend\extensions\BlockListKino;
use yii\helpers\Url;

/**
 * Description of BlockListKino
 *
 * @author dima
 */
class BlockListKino extends \yii\base\Widget 
{
    public $title;
    public $template ="index";    
    public $className;
    public $attribute;
    public $limit =0;
    public $path = 'business/index';
    public $p = false;
    public $id_category = null;
    
    public function run()
    {
        $class = $this->className;
        $models = $class::find(); 
        
        $value = $this->p;
        
        $rootCat = null;
        
        if($value || $value = \Yii::$app->request->getQueryParam($this->attribute)){     // $pid != null, 
//            $rootCat = $rid['pid'];
//            
//            if($rootCat) $value=$rootCat;
            
//            $models->where([$this->attribute => $value]);
            $models->where([$this->attribute => $this->id_category]);
            $models = $models->orderBy('title')->all();
            
            if(!$models) {
                $rid = $class::find()->select(['pid'])->where(['url'=>$value])->one();       // родитель
                $models = $class::find()->where(['pid'=>$rid->pid])->orderBy('title')->all();
            }
        }
        else { // все предприятия, $pid = null
            
            $models = $models->orderBy('title')->all();      
        }
        
        return $this->render($this->template, [
            'models' =>$models,

        ]);
    }
    
    public function createUrl($param = null)
    {
        if($param){
           return Url::to([$this->path,$this->attribute => $param]); 
        }
        else{
            return Url::to([$this->path]); 
        }
    }
}
