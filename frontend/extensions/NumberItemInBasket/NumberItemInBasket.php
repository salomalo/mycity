<?php
namespace frontend\extensions\NumberItemInBasket;

use frontend\controllers\ShoppingCartController;
use yii\base\Widget;

class NumberItemInBasket extends Widget
{
    public function run(){
        $count = ShoppingCartController::getNumbetItem();

        return $this->render('index', [
            'numberItems' => $count,
        ]);
    }
}