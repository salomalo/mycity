<?php
/**
 * Created by PhpStorm.
 * User: nautilus
 * Date: 6/15/14
 * Time: 4:16 PM
 */

namespace office\extensions\BlockList;
use yii\helpers\Url;
use common\models\BusinessCategory;

class BlockLis extends \yii\base\Widget
{
    public $title;
    public $className;
    public $attribute;

    public function run()
    {
        /**@var $models \yii\db\ActiveRecord[] */
        /**@var $class \yii\db\ActiveRecord */
        $class = $this->className;
        $models = $class::find();  
        
        //$models = $models->all();
        if($value = \Yii::$app->request->getQueryParam($this->attribute)){     // $pid != null, 
            $rid = BusinessCategory::find()->select(['pid'])->where(['id'=>$value])->one();
            $rootCat = $rid['pid'];
            
            if($rootCat) $value=$rootCat;
            
            $models->andFilterWhere([$this->attribute => $value]);
            $models = $models->all();
            
            if(!$models) {
                $models = $class::find()->where(['pid'=>null])->all();
            }
        }
        else { // все предприятия, $pid = null
            $models = $models->where(['pid'=>null])->all();
        }
       
        return $this->render('index', [
            'models' =>$models,
        ]);
    }

    public function createUrl($param)
    {
        return Url::to(['business/index',$this->attribute => $param]);
    }
} 