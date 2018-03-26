<?php
namespace console\controllers;

use common\models\ViewCount;
use yii;
use yii\console\Controller;

/**
 * ParserController parse HotLine products
 */
class ViewCountController extends Controller
{
    public function actionAddCity()
    {
        $query = ViewCount::find()->where(['category' => ViewCount::CAT_BUSINESS, 'city_id' => null]);
        echo $query->count();

        /** @var ViewCount[] $models */
        foreach ($query->batch() as $models) {
            foreach ($models as $model) {
                if (is_object($model->model) and $model->model->idCity) {
                    $model->updateAttributes(['city_id' => $model->model->idCity]);
                    echo '.';
                } else {
                    echo ',';
                }
            }
        }
        echo PHP_EOL;
    }
}