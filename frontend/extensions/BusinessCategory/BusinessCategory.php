<?php

namespace frontend\extensions\BusinessCategory;

use common\models\Business;
use common\models\BusinessCategory as BusinessCategoryModel;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;

class BusinessCategory extends Widget
{
    public $pid;
    public $img;
    public $icon;
    public $color;

    public function run()
    {
        $model = BusinessCategoryModel::findOne(['url' => $this->pid]);
        $models = array_merge($model->children()->all(), [$model]);
        $ids = implode(',', ArrayHelper::map($models,'id','id'));
        $countQuery = Business::find()->where('"idCategories" && \'{' . $ids . '}\'' );
        return $this->render('item',[
            'count' => $countQuery->city()->count(),
            'model' => $model,
        ]);
    }
}