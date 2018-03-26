<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\BusinessCategory;

/**
 * Description of BusinessCategoryNestedController
 *
 * @author dima
 */
class BusinessCategoryNestedController extends Controller {

    public function actionMakeRoots(){
        foreach (BusinessCategory::find()->where('lft is NULL')->orderBy('id ASC')->batch(100) as $b){
        
            foreach ($b as $item){
                if($item->root != $item->id){
                    $this->log($item->title, false);
                    $this->log(' - Добавленно в ROOT', true, 'green');
                    $item->lft = 1;
                    $item->rgt = 2;
                    $item->root = $item->id;
                    $item->depth = 0;
                    $item->save();
                }
            }
            
        }

    }
    
    public function actionMakeChildrens(){
        
        $models = BusinessCategory::find()->where('pid is NULL')->orderBy('id ASC')->all();
        
        foreach ($models as $item){
//            $transactionRoot = $item->getDb()->beginTransaction();
            $this->log($item->title, true);
            foreach (BusinessCategory::find()->where(['pid' => $item->id])->batch(100) as $b){
               
                foreach ($b as $ch1){
//                     $transaction = $ch1->getDb()->beginTransaction();
                    
                    $this->log(' - ' . $ch1->title, false);
                    if($ch1->appendTo($item)){
                        $this->log(' - Добавленна 1 вложенность', true, 'green');
                    }
                    else{
                        $this->log(' - Не добавленно', true, 'red');
                    }

                    foreach (BusinessCategory::find()->where(['pid' => $ch1->id])->all() as $ch2){
                        $this->log(' -- ' . $ch2->title, false);
                        if($ch2->appendTo($ch1)){
                            $this->log(' - Добавленна 2 вложенность', true, 'green');
                        }
                        else{
                            $this->log(' - Не добавленно', true, 'red');
                        }
                    }
//                    $transaction->commit();
                }
                
            }
//            $transactionRoot->commit();    
        }
    }
    
    function log($string, $br = false, $color = false, $db = false)
    {
        if(!$color){
            echo $string;
        }
        elseif($color == 'red' || $color == 'r'){
            echo "\033[31m" . $string . "\033[0m";
        }
        elseif($color == 'green' || $color == 'g'){
            echo "\033[32m" . $string . "\033[0m";
        }
        
        if($br){
            echo "\n";
        }
        
        
    }
}
