<?php
namespace backend\models\search;

use common\models\search\Business as BusinessSearch;
use yii;

class Business extends BusinessSearch
{
    protected function getModelQuery()
    {
        return parent::getModelQuery()->andWhere(['idCity' => Yii::$app->params['adminCities']['id']]);
    }
}
