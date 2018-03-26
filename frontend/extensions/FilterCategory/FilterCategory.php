<?php

namespace frontend\extensions\FilterCategory;

use common\models\Ads;
use common\models\Business;
use common\models\ProductCategory;
use yii\base\Widget;

class FilterCategory extends Widget
{
    public $businessModel;

    public function run()
    {
        /** @var Ads[] $ads */
        $ads = Ads::find()->where(['idBusiness' => $this->businessModel->id])->all();
        $adsCategory = [];
        foreach ($ads as $ad){
            if ($ad->idCategory && !array_key_exists($ad->idCategory, $adsCategory)){
                $category = ProductCategory::findOne($ad->idCategory);
                $adsCategory[$category->id] =  $category;
            }
        }

        $sortFunction = function ($x, $y) {
            return strcasecmp($x->title, $y->title);
        };

        usort($adsCategory, $sortFunction);

        if ($ads) {
            /** @var Ads $model */
            $model = Ads::find()->where(['idBusiness' => $this->businessModel->id])->orderBy(['price' => SORT_ASC])->one();
            $minPrice = $model->price;
            $model = Ads::find()->where(['idBusiness' => $this->businessModel->id])->orderBy(['price' => SORT_DESC])->one();
            $maxPrice = $model->price;
        } else {
            $minPrice = 0;
            $maxPrice = 0;
        }

        if (!empty($adsCategory)) {
            return $this->render('index', [
                'model' => $this->businessModel,
                'adsCategories' => $adsCategory,
                'minPrice' => $minPrice,
                'maxPrice' => $maxPrice,
            ]);
        }
    }
}