<?php
namespace frontend\extensions\BlockListAfisha;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class BlockListAfisha extends Widget
{
    public $title;
    public $template = 'index';
    public $className;
    public $attribute;
    public $limit = 0;
    public $path = 'business/index';
    public $p = false;
    public $id_category = null;
    public $archive = null;
    public $forWeek = null;

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
        $options = [$this->path];
        if ($param) {
            $options[$this->attribute] = $param;
        }
        if ($this->archive) {
            $options['archive'] = $this->archive;
        } elseif (Yii::$app->request->get('time')) {
            $options['time'] = Yii::$app->request->get('time');
        }
        return Url::to($options);
    }
} 