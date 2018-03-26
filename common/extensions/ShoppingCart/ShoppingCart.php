<?php

namespace common\extensions\ShoppingCart;

use Yii;
use yii\base\Widget;
use common\models\ShoppingCart as ShoppingCartModel;

/**
 * Description of ShoppingCart
 *
 * @author dima
 */
class ShoppingCart extends Widget {
    
    public function run()
    {     
        $model = new ShoppingCartModel();
        echo $this->render('index',[
              'model' => $model
              ]); 
    }
    
    
}
