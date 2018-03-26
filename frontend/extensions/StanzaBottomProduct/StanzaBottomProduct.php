<?php

namespace frontend\extensions\StanzaBottomProduct;

use common\models\Ads;
use yii\base\Widget;

class StanzaBottomProduct extends Widget
{
    public $businessModel;
    public $limit = 3;

    public function run()
    {
        $newProducts = Ads::find()
            ->where(['idBusiness' => $this->businessModel->id])
            ->limit($this->limit)
            ->all();

        $topRatingProducts = Ads::find()
            ->where(['idBusiness' => $this->businessModel->id])
            ->orderBy(['rating' => SORT_DESC])
            ->limit($this->limit)
            ->all();

        $featuredProducts = Ads::find()
            ->where(['idBusiness' => $this->businessModel->id])
            ->orderBy(['views' => SORT_DESC])
            ->limit($this->limit)
            ->all();

        $onSaleProducts = Ads::find()
            ->where(['idBusiness' => $this->businessModel->id])
            ->andWhere(['not', 'discount', ''])
            ->limit($this->limit)
            ->all();


        return $this->render('index', [
            'newProducts' => $newProducts,
            'topRatingProducts' => $topRatingProducts,
            'featuredProducts' => $featuredProducts,
            'onSaleProducts' => $onSaleProducts,
            'business' => $this->businessModel
        ]);
    }
}