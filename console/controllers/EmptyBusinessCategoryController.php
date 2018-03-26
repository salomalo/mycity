<?php

namespace console\controllers;

use common\models\ProductCategory;
use yii\console\Controller;
use common\models\Business;
use common\models\BusinessCategory;
use yii\helpers\ArrayHelper;

/**
 * Description of EmptyBusinessCategoryController
 *
 * @author dima
 */
class EmptyBusinessCategoryController extends Controller
{
    public function actionIndex($idCity, $idEmptyCategory = 6645)
    {
        $this->log('Start find' . "\n"); 
        
        foreach (Business::find()->where(['idCity' => $idCity])->batch(100) as $b){
            foreach ($b as $model){
                
                if(count($model->idCategories) == 1){   // 1 категория
                    foreach ($model->idCategories as $cat){
                        $category = ($cat)? BusinessCategory::findOne(['id' => $cat]) : false;
                        if(!$category){ 
                            $model->idCategories = [$idEmptyCategory];
                            $model->save(false, ['idCategories']);
                        }
                    }
                } else {    // несколько категорий
                    
                    $arr = [];
                    foreach ($model->idCategories as $cat){
                        $category = ($cat)? BusinessCategory::findOne(['id' => $cat]) : false;
                        
                        if($category){
                            $arr[] = $category->id;
                        }
                        else{
                            $this->log($model->id . ' - ' . $model->title . ' - ');
                            $this->log($cat . ', ');
                            $this->log('-нет', true);
                            echo "\n";
                        } 
                    } 
                    
                    if($model->idCategories != $arr){
                        if(count($arr) > 0){
                            $model->idCategories = $arr;
                        }
                        else{
                            $model->idCategories = [$idEmptyCategory];
                        }
                        
                        $model->save(false, ['idCategories']);
                    }
                }
                 
            }
        }
        
        $this->log('End find' . "\n"); 
        
    }

    public function log($string, $fail = false)
    {
        if($fail){
            echo "\033[31m" . $string . "\033[0m";
        }
        else{
            echo $string ;
        }
    }

    public function actionCheckAll($emptyCat = 6645, $where = null)
    {
        $query = Business::find()->orderBy('id');
        if ($where) {
            $query->where(['>=', 'id', $where]);
        }
        $all_business_cats = ArrayHelper::getColumn((BusinessCategory::find()->select('id')->all()), ['id']);
        $all_products_cats = ArrayHelper::getColumn((ProductCategory::find()->select('id')->all()), ['id']);
        foreach ($query->batch() as $models) {
            /** @var Business $model */
            foreach ($models as $model) {
                if (!empty($model->idCategories) and is_array($model->idCategories)) {

                    $new_cats = [];
                    foreach ($model->idCategories as $category) {
                        if (in_array((int)$category, $all_business_cats)) {
                            $new_cats[] = (int)$category;
                        }
                    }
                    $model->idCategories = empty($new_cats) ? [$emptyCat] : $new_cats;


                    $new_cats = [];
                    foreach ($model->idProductCategories as $category) {
                        if (in_array((int)$category, $all_products_cats)) {
                            $new_cats[] = (int)$category;
                        }
                    }
                    $model->idProductCategories = $new_cats;

                    echo 'Предприятие: ', $model->id,  PHP_EOL;
                    $model->beforeSave(false);
                    $model->updateAttributes(['idCategories', 'idProductCategories']);
                } else {
                    continue;
                }
            }
        }
    }
}
