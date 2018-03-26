<?php

namespace frontend\extensions\KuteshopFloorProduct;


use common\models\Ads;
use common\models\Business;
use common\models\ProductCategory;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class KuteshopFloorProduct extends Widget
{
    public $idFloor;
    /** @var  $productCategory ProductCategory */
    public $productCategory;
    public $key;
    public $limit = 8;
    /** @var  $business Business */
    public $business;

    public function run()
    {
        $children = $this->productCategory->children(1)->all();
        /** @var ProductCategory[] $resultProductCategory */
        $resultProductCategory = [];
        //Добавляем в массив не пустые категории
        foreach ($children as $model) {
            if (count($resultProductCategory) == 12){
                break;
            }
            //Проверяем является ли категория листом
            /** @var ProductCategory $model */
            if ($model->isLeaf()) {
                $ads = Ads::findOne(['idCategory' => $model->id, 'idBusiness' => $this->business->id]);
                if ($ads) {
                    $resultProductCategory[] = $model;
                }
            } else {
                /** @var ProductCategory[] $child */
                $child = $model->children()->all();
                foreach ($child as $ch) {
                    //если есть обьвления в детей то добавляет категория в массив
                    $ads = Ads::findOne(['idCategory' => $ch->id, 'idBusiness' => $this->business->id]);
                    if ($ads) {
                        $resultProductCategory[] = $model;
                        break;
                    }
                }
            }
        }

        $childCat = ArrayHelper::getColumn($this->productCategory->children()->all(), 'id');

        $whereInCategory = [
            'or',
            ['idCategory' => $this->productCategory->id],
            ['idCategory' => $childCat]
        ];

        $newProducts = Ads::find()
            ->where(['idBusiness' => $this->business->id])
            ->andWhere($whereInCategory)
            ->limit($this->limit)
            ->all();

        $onSaleProducts = Ads::find()
            ->where(['idBusiness' => $this->business->id])
            ->andWhere($whereInCategory)
            ->andWhere(['and',
                ['not', 'discount', ''],
                ['not', 'discount', ['$eq' => null]]
            ])
            ->limit($this->limit)
            ->all();

        $topRatingProducts = Ads::find()
            ->where(['idBusiness' => $this->business->id])
            ->andWhere($whereInCategory)
            ->orderBy(['views' => SORT_DESC])
            ->limit($this->limit)
            ->all();

        $specialProduct = Ads::find()
            ->where(['idBusiness' => $this->business->id])
            ->andWhere($whereInCategory)
            ->andWhere(['isShowOnBusiness' => '1'])
            ->limit($this->limit)
            ->all();

        return $this->render('index', [
            'idFloor' => $this->idFloor,
            'productCategory' => $this->productCategory,
            'child' => $resultProductCategory,
            'key' => $this->key,
            'newProducts' => $newProducts,
            'onSaleProducts' => $onSaleProducts,
            'topRatingProducts' => $topRatingProducts,
            'specialProduct' => $specialProduct,
            'business' => $this->business,
        ]);
    }
}