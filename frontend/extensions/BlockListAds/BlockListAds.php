<?php
namespace frontend\extensions\BlockListAds;

use common\models\Ads;
use common\models\ProductCategory;
use common\models\ProductCategoryQuery;
use yii\base\Widget;

class BlockListAds extends Widget
{
    public $template = 'super_list_goods';
    public $idCategory = null;
    public $business;

    public function run()
    {
        /** @var $query ProductCategoryQuery */
        $query = null;
        $value = $this->idCategory ? $this->idCategory : null;

        if (!$value) {
            $query = ProductCategory::find()->orderBy('title')->roots();
        } else {
            if ($this->idCategory) {
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

        return $this->render($this->template, [
            'models' => $resultModels,
            'business' => $this->business,
            'idCategory' => $this->idCategory,
            'rootCat' => $value ? $value : null,
        ]);
    }
}