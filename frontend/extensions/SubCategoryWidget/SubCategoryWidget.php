<?php

namespace frontend\extensions\SubCategoryWidget;

use common\models\Ads;
use common\models\Business;
use common\models\ProductCategory;
use yii\base\Widget;

class SubCategoryWidget extends Widget
{
    /** @var Business | null  */
    public $business = null;
    /** @var  ProductCategory | null */
    public $category = null;

    public function run()
    {
        if ($this->category && $this->category->isLeaf()) {
            return $this->render('index', [
                'models' => null,
                'category' => $this->category,
                'business' => $this->business,
            ]);
        }


        /** @var $query ProductCategoryQuery */
        $query = null;
        $value = $this->category->id ? $this->category->id: null;

        if (!$value) {
            $query = ProductCategory::find()->orderBy('title')->roots();
        } else {
            if ($this->category->id) {
                $category = ProductCategory::findOne(['id' => $value]);
            } else {
                $category = ProductCategory::findOne(['title' => $value]);
            }

            if (!$category) {
                $query = ProductCategory::find()->orderBy('title')->roots();
            } else {
                $childrens = $category->children(1)->orderBy('title')->all();
                if ($childrens) {
                    $query = $category->children(1)->orderBy('title');
                } else {
                    if ($category->root) {
                        $query = ProductCategory::find()->orderBy('title')->roots();
                    }

                    /** @var  ProductCategory $categoryParent */
                    $categoryParent = ProductCategory::find()->where(['id' => $value])->one();
                    /** @var  ProductCategory $parent */
                    $parent = $categoryParent->parents(1)->one();

                    if ($parent) {
                        $query = $parent->children(1)->orderBy('title');
                    }
                }
            }
        }
        //$query->andWhere(['sitemap_en' => 1]);
        $models = $query->all();

        /** @var ProductCategory[] $resultModels */
        $resultModels = [];
        //Добавляем в массив не пустые категории
        foreach ($models as $model) {
            //Проверяем является ли категория листом
            /** @var ProductCategory $model */
            if ($model->isLeaf()) {
                $ads = Ads::findOne(['idCategory' => $model->id, 'idBusiness' => $this->business->id]);
                if ($ads) {
                    $resultModels[] = $model;
                }
            } else {
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
        }

        return $this->render('index', [
            'models' => $resultModels,
            'category' => $this->category,
            'business' => $this->business,
        ]);
    }
}