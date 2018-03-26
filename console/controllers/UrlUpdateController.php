<?php
namespace console\controllers;

use Yii;
use yii\base\ErrorException;
use yii\console\Controller;
use common\models\Product;
use common\models\Ads;
use common\models\Post;
use common\models\Afisha;
use common\models\Action;
use common\models\Business;
use common\models\WorkResume;
use common\models\WorkVacantion;

/**
 * ParserController parse HotLine products
 */
class UrlUpdateController extends Controller
{
    
    public function actionUpdate()
    {
        
//        $this->UpdateWorkResume();
//        $this->UpdateWorkVacantion();
//        $this->UpdateAction();
//        $this->UpdateAfisha();
//        $this->UpdatePost();
        $this->actionBusiness();
//        $this->UpdateAds();
//        $this->actionProduct();
        
    }
    
    public function actionCategory($name){
       $this->log("\n *** Category {$name} *** \n",true);
       $size = 100; 
       $m = "\common\models\\".$name;
       $model = new $m;
       $count = $model::find()->count();
       $pages = ceil($count/$size);  
       
       for ($i = 1; $i <= $pages; $i++) {
         $offset = ($i-1)*$size;  
         $products = $model::find()->limit($size)->offset($offset)->orderBy('id')->all();      
         $this->log("Страница".$i." из ".$pages."\n");
         $transaction = $model->getDb()->beginTransaction();
         foreach ($products as $item) {   
           if (empty($item->url)){  
               $item->save();
           }     
         }
         $transaction->commit();         
       }  
       $this->log("\n *** End Update Category {$name} *** \n",true);
    }
    
    public function actionProduct($start=null){
       $this->log("\n *** UpdateProduct *** \n",true);
       $size = 100; 
       $count = Product::find()->count();
       $pages = ceil($count/$size);       
       $this->log(" Всего Страниц: ".$pages."\n");
       $this->log("Всего Записей: ".$count."\n"); 
       for ($i = ($start)? $start : 1; $i <= $pages; $i++) {
         $offset = ($i-1)*$size;  
         $products = Product::find()->limit($size)->offset($offset)->orderBy('_id')->all();      
         $this->log("\n Страница".$i."\n",1);  
         foreach ($products as $item) {  
             echo $item->url." \n";
           if (empty($item->url)){  
                $this->log("Product Запись id = ".$item->_id."  Результат =".$item->save()." - Страница".$i."\n");
           }
           else{
                $this->log("Product Запись id = ".$item->_id."  Пропущена т.к. существует ".$item->url." - Страница".$i."\n");
           }
         }     
       }
       $this->log("\n *** End UpdateProduct *** \n",true);
    }
    
    private function UpdateAds(){
       $this->log("\n *** UpdateAds *** \n",true);
       $size = 2000; 
       $count = Ads::find()->count();
       $pages = ceil($count/$size);       
       $this->log(" Всего Страниц: ".$pages."\n");
       $this->log("Всего Записей: ".$count."\n"); 
       $start =isset($start)?$start:1;       
       for ($i = $start; $i <= $pages; $i++) {
         $offset = ($i-1)*$size;  
         $products = Ads::find()->limit($size)->offset($offset)->orderBy('_id')->all();      
         $this->log("\n Страница".$i."\n");
         foreach ($products as $item) {   
           if (empty($item->url)){  
            $this->log("Ads Запись id = ".$item->_id."  Результат =".$item->save()."\n");
           }
           else{
                $this->log("Ads Запись id = ".$item->_id."  Пропущена т.к. существует ".$item->url."\n");
           }
         }     
       }
       $this->log("\n *** End UpdateAds *** \n",true);
    }
 
    
    public function actionBusiness($start=null){
       $this->log("\n *** UpdateBusiness *** \n",true);
       $size = 100; 
       $count = Business::find()->count();
       $pages = ceil($count/$size);       
       $this->log(" Всего Страниц: ".$pages."\n");
       $this->log("Всего Записей: ".$count."\n"); 
       $start =isset($start)?$start:1;
       for ($i = $start; $i <= $pages; $i++) {
         $offset = ($i-1)*$size;  
         $products = Business::find()->limit($size)->offset($offset)->orderBy('id')->all();      
         $this->log("Страница".$i." из ".$pages."\n");         
         $model = new Business();
         $transaction = $model->getDb()->beginTransaction();
         foreach ($products as $item) {   
           if (empty($item->url)){  
               $item->save();
           }     
         }
         $transaction->commit();         
       }  
       $this->log("\n *** End UpdateBusiness *** \n",true);
       }  
    
    private function UpdateWorkResume(){
       $this->log("\n *** UpdateWorkResume *** \n",true);
       $size = 2000; 
       $count = WorkResume::find()->count();
       $pages = ceil($count/$size);       
       $this->log(" Всего Страниц: ".$pages."\n");
       $this->log("Всего Записей: ".$count."\n"); 
       for ($i = 1; $i <= $pages; $i++) {
         $offset = ($i-1)*$size;  
         $products = WorkResume::find()->limit($size)->offset($offset)->orderBy('id')->all();      
         $this->log("\n Страница".$i."\n");
         foreach ($products as $item) {   
           if (empty($item->url)){  
                $this->log("WorkResume Запись id = ".$item->id."  Результат =".$item->save()."\n");
           }
           else{
                $this->log("WorkResume Запись id = ".$item->id."  Пропущена т.к. существует ".$item->url."\n");
           }
             
         }     
       }
       $this->log("\n *** End UpdateWorkResume *** \n",true);
    }
    
    private function UpdateWorkVacantion(){
       $this->log("\n *** UpdateWorkVacantion *** \n",true);
       $size = 2000; 
       $count = WorkVacantion::find()->count();
       $pages = ceil($count/$size);       
       $this->log(" Всего Страниц: ".$pages."\n");
       $this->log("Всего Записей: ".$count."\n"); 
       for ($i = 1; $i <= $pages; $i++) {
         $offset = ($i-1)*$size;  
         $products = WorkVacantion::find()->limit($size)->offset($offset)->orderBy('id')->all();      
         $this->log("\n Страница".$i."\n");
         foreach ($products as $item) {   
           if (empty($item->url)){  
                $this->log("WorkVacantion Запись id = ".$item->id."  Результат =".$item->save()."\n");
           }
           else{
                $this->log("WorkVacantion Запись id = ".$item->id."  Пропущена т.к. существует ".$item->url."\n");
           }
         }     
       }
       $this->log("\n *** End UpdateWorkVacantion *** \n",true);
    }
    
    private function UpdateAction(){
       $this->log("\n *** UpdateAction *** \n",true);
       $size = 2000; 
       $count = Action::find()->count();
       $pages = ceil($count/$size);       
       $this->log(" Всего Страниц: ".$pages."\n");
       $this->log("Всего Записей: ".$count."\n"); 
       for ($i = 1; $i <= $pages; $i++) {
         $offset = ($i-1)*$size;  
         $products = Action::find()->limit($size)->offset($offset)->orderBy('id')->all();      
         $this->log("\n Страница".$i."\n");
         foreach ($products as $item) {   
           if (empty($item->url)){  
                $this->log("Action Запись id = ".$item->id."  Результат =".$item->save()."\n");
           }
           else{
                $this->log("Action Запись id = ".$item->id."  Пропущена т.к. существует ".$item->url."\n");
           }
         }     
       }
       $this->log("\n *** End UpdateAction *** \n",true);
    }

    private function UpdateAfisha(){
       $this->log("\n *** UpdateAfisha *** \n",true);
       $size = 2000; 
       $count = Afisha::find()->count();
       $pages = ceil($count/$size);       
       $this->log(" Всего Страниц: ".$pages."\n");
       $this->log("Всего Записей: ".$count."\n"); 
       for ($i = 1; $i <= $pages; $i++) {
         $offset = ($i-1)*$size;  
         $products = Afisha::find()->limit($size)->offset($offset)->orderBy('id')->all();      
         $this->log("\n Страница".$i."\n");
         foreach ($products as $item) {   
           if (empty($item->url)){  
                $this->log("Afisha Запись id = ".$item->id."  Результат =".$item->save()."\n");
           }
           else{
                $this->log("Afisha Запись id = ".$item->id."  Пропущена т.к. существует ".$item->url."\n");
           }
         }     
       }
       $this->log("\n *** End UpdateAfisha *** \n",true);
    }
    private function UpdatePost(){
       $this->log("\n *** UpdatePost *** \n",true);
       $size = 2000; 
       $count = Post::find()->count();
       $pages = ceil($count/$size);       
       $this->log(" Всего Страниц: ".$pages."\n");
       $this->log("Всего Записей: ".$count."\n"); 
       for ($i = 1; $i <= $pages; $i++) {
         $offset = ($i-1)*$size;  
         $products = Post::find()->limit($size)->offset($offset)->orderBy('id')->all();      
         $this->log("\n Страница".$i."\n");
         foreach ($products as $item) {   
           if (empty($item->url)){  
                $this->log("Post Запись id = ".$item->id."  Результат =".$item->save()."\n");
           }
           else{
                $this->log("Post Запись id = ".$item->id."  Пропущена т.к. существует ".$item->url."\n");
           }
         }     
       }
       $this->log("\n *** End UpdatePost *** \n",true);
    }
    
    
    private function log($string, $fail = false)
    {
        if($fail){
            echo "\033[31m" . $string . "\033[0m";
        }
        else{
            echo $string ;
        }
    }


    public function actionUpdateBusiness()
    {
        $s = '%\u0___%';
        $where = [
            'or',
            ['~~*', 'title', $s],
            ['~~*', 'description', $s],
            ['~~*', 'shortDescription', $s]
        ];

        $i = Business::find()->where($where)->count();
        echo 'Всего:', PHP_EOL, $i, "\t";
        foreach (Business::find()->where($where)->batch() as $models) {
            /** @var $models Business[] */
            foreach ($models as $model) {
                $model->save();
                echo !(--$i % 50) ? PHP_EOL . $i . "\t" : '.';
            }
        }
        echo PHP_EOL, 'Готово!', PHP_EOL;
    }
}