<?php

namespace office\extensions\BlockBusinessOrder;

use common\models\OrdersAds;
use Yii;

class BlockBusinessOrder  extends \yii\base\Widget
{
    public $business;

    public function run()
    {
        if (Yii::$app->user->identity->id) {
            if (isset($this->business) && $this->business) {
                $countOrder = OrdersAds::find()->where(['idBusiness' => $this->business->id])->count();
            } else {
                $countOrder = 0;
            }
        } else {
            $countOrder = 0;
        }

        return $this->render('index', [
            'countOrder' => $countOrder,
        ]);
    }
}