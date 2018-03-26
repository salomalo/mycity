<?php
namespace backend\models\search;

use backend\models\Admin;
use common\models\search\Orders as OrdersSearch;
use yii;

class Orders extends OrdersSearch
{
    protected function getModelQuery()
    {
        $admin = Yii::$app->user->identity;
        if ($admin->level === Admin::LEVEL_SUPER_ADMIN){
            return parent::getModelQuery();
        } else {
            return parent::getModelQuery()->andWhere(['idCity' => Yii::$app->params['adminCities']['id']]);
        }

    }
}
