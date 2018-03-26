<?php
namespace frontend\extensions\Actions;

class ActionController extends Controller {
    
//     public function actionIndex($pid = null, $sort = null, $time = null) {
//   
//        return $this->render('index', [
//        ]);
//    } 
     public function actionCalendar($date) {
        
        return $this->render('index', [
        ]);
    } 
    
    
}
