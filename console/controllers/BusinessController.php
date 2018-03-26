<?php

namespace console\controllers;

use common\models\File;
use common\models\StarRating;
use yii;
use yii\console\Controller;

class BusinessController extends Controller
{
    public function actionFixRating()
    {
        $query = StarRating::find()->where(['object_type' => null])->orderBy('id');
        /** @var StarRating[] $models */
        foreach ($query->batch() as $models) {
            foreach ($models as $model) {
                $model->object_type = File::TYPE_BUSINESS;
                $model->save();
            }
        }
    }
}
