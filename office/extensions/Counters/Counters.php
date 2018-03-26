<?php
namespace office\extensions\Counters;

use yii\base\Widget;

class Counters extends Widget
{
     public function run()
     {
         if (YII_ENV === 'prod') {
             return $this->render('index');
         }
         return null;
     }
}