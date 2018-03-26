<?php
/**
 * Created by PhpStorm.
 * User: nautilus
 * Date: 6/15/14
 * Time: 4:16 PM
 */

namespace frontend\extensions\BlockList;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;

class BlockList extends \yii\base\Widget
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
        /**@var $models \yii\db\ActiveRecord[] */
        /**@var $class \yii\db\ActiveRecord */
        $class = $this->className;
        $models = $class::find(); 
        
        $value = $this->p;
        
        $rootCat = null;
        
        if($value || $value = \Yii::$app->request->getQueryParam($this->attribute))
        {
            $models->where([$this->attribute => $this->id_category]);
            $models = $models->all();
            
            if(!$models) {
                $rid = $class::find()->select(['pid'])->where(['url'=>$value])->one();       // родитель
                $models = $class::find()->where(['pid'=>$rid->pid])->all();
            }
        }
        else { // все предприятия, $pid = null
            if ($this->limit>0){
                $models = $models->limit($this->limit);
            }
            
            $pid = null;
            
            if($this->id_category){ // для view
                $temp = new $this->className;
                $pid = $temp::find()->select('pid')->where(['id' => $this->id_category])->one();
            }

            $models = $models->where(['pid'=> ($pid)? $pid->pid : null])->all();
            
        }
       
        return $this->render($this->template, [
            'models' =>$models,
            'rootCat' =>($value)? $value : null,
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