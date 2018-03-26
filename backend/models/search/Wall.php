<?php
namespace backend\models\search;

use common\models\search\Wall as WallSearch;
use yii;

class Wall extends WallSearch
{
    protected function getModelQuery()
    {
        return parent::getModelQuery()->andWhere(['idCity' => Yii::$app->params['adminCities']['id']]);
    }
}
