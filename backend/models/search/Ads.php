<?php
namespace backend\models\search;

use common\models\search\Ads as AdsSearch;
use yii;

class Ads extends AdsSearch
{
    protected function getModelQuery()
    {
        return parent::getModelQuery()->andWhere(['idCity' => Yii::$app->params['adminCities']['id']]);
    }
}
