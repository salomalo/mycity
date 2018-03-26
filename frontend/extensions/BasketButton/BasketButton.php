<?php
namespace frontend\extensions\BasketButton;
use common\models\Ads;
use frontend\controllers\ShoppingCartController;
use yii\base\Widget;


class BasketButton extends  Widget
{
    public $model_id;
    public $template = 'index';
    public $alias = 'default';

    public function init()
    {
        parent::init();
        Assets::register($this->view);
    }

    public function run(){
        $model = Ads::find()->where(['_id' => $this->model_id])->one();

        return $this->render($this->template,[
            'model' => $model,
            'isInBasket' => ShoppingCartController::isInBasket($this->model_id),
            'alias' => $this->alias,
        ]);
    }
}