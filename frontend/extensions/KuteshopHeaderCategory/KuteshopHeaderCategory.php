<?php

namespace frontend\extensions\KuteshopHeaderCategory;

use common\models\Ads;
use common\models\Business;
use common\models\ProductCategory;
use yii\base\Widget;

class KuteshopHeaderCategory extends Widget
{
    /** @var  Business */
    public $business;


    public function run()
    {
        /** @var ProductCategory[] $productCategory */
        $productCategory = ProductCategory::find()->roots()->all();

        /** @var ProductCategory[] $resultModels */
        $resultModels = [];
        //Добавляем в массив не пустые категории
        foreach ($productCategory as $model) {
            /** @var ProductCategory[] $child */
            $child = $model->children()->all();
            foreach ($child as $ch) {
                //если есть обьвления в детей то добавляет категория в массив
                $ads = Ads::findOne(['idCategory' => $ch->id, 'idBusiness' => $this->business->id]);
                if ($ads) {
                    $resultModels[] = $model;
                    break;
                }
            }
        }

        return $this->render('index', [
            'productCategory' => $resultModels,
            'business' => $this->business,
        ]);
    }
}