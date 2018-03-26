<?php
namespace console\controllers;

use common\models\User;
use yii;
use yii\console\Controller;

/**
 * ParserController parse HotLine products
 */
class UserController extends Controller
{
    public function actionAddLastActivity()
    {
        $query = User::find()->where(['last_activity' => null]);
        echo $query->count();

        /** @var User[] $models */
        foreach ($query->batch() as $models) {
            foreach ($models as $model) {
                $model->updateAttributes(['last_activity' => date('Y-m-d H:i:s', $model->created_at)]);
                echo '.';
            }
        }
        echo PHP_EOL;
    }
}