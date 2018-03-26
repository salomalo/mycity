<?php
namespace frontend\extensions\BlockListProduct;

use common\models\Ads;
use common\models\ProductCategory;
use common\models\ProductCategoryQuery;
use yii;
use yii\base\Widget;
use yii\helpers\Url;

class BlockListProduct extends Widget
{
    public $title;
    public $className;
    public $attribute;
    public $path = 'product/index';
    public $limit = null;
    public $template = 'index';
    public $city = '';
    public $id_category = null;
    public $categories;

    public function run()
    {
        /** @var $class \yii\db\ActiveRecord */
        /** @var $query ProductCategoryQuery*/
        /** @var $models ProductCategory[]*/
        $class = $this->className;
        $query = null;

        if ($this->id_category){
            $value = $this->id_category;
        } else {
            $value = Yii::$app->request->getQueryParam($this->attribute);
        }

        if ($this->categories) {
            $query = $class::find()->where(['id' => $this->categories])->orderBy('title');
        } elseif (!$value) {
            $query = $class::find()->orderBy('title')->roots();
        } else {
            if ($this->id_category) {
                $category = $class::findOne(['id' => $value]);
            } else {
                $category = $class::findOne(['title' => $value]);
            }

            if (!$category) {
                $query = $class::find()->orderBy('title')->roots();
            } else {
                $childrens = $category->children(1)->orderBy('title')->all();

                if ($childrens) {
                    $query = $category->children(1)->orderBy('title');
                } else {
                    if ($category->root) {
                        $query = $class::find()->orderBy('title')->roots();
                    }

                    $categoryParent = $class::find()->where(['id' => $value])->one();
                    $parent = $categoryParent->parents(1)->one();

                    if ($parent) {
                        $query = $parent->children(1)->orderBy('title');
                    }
                }
            }
        }

        $query->andWhere(['sitemap_en' => 1]);
        $models = $query->all();

        $resultModels = [];
        if ($this->path == 'ads/index') {
            /** @var ProductCategory[] $resultModels */
            //Добавляем в массив не пустые категории
            foreach ($models as $model) {
                //Проверяем является ли категория листом
                if ($model->isLeaf()) {
                    $ads = Ads::findOne(['idCategory' => $model->id]);
                    if ($ads) {
                        $resultModels[] = $model;
                    }
                } else {
                    /** @var ProductCategory[] $child */
                    $child = $model->children()->all();
                    foreach ($child as $ch) {
                        //если есть обьвления в детей то добавляет категория в массив
                        $ads = Ads::findOne(['idCategory' => $ch->id]);
                        if ($ads) {
                            $resultModels[] = $model;
                            break;
                        }
                    }
                }
            }
        } else {
            $resultModels = $models;
        }

        return $this->render($this->template, [
            'models' => $resultModels,
            'city' => $this->city,
            'rootCat' => $value ? $value : null,
        ]);
    }

    public function createUrl($param = null)
    {
        return $param ? Url::to([$this->path,$this->attribute => $param]) : Url::to([$this->path]);
    }
}
