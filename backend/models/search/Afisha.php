<?php
namespace backend\models\search;

use common\models\search\Afisha as AfishaSearch;
use yii;

class Afisha extends AfishaSearch
{
    protected function getModelQuery()
    {
        $cookies = Yii::$app->request->cookies;

        $query = parent::getModelQuery()->andWhere(['business."idCity"' => Yii::$app->params['adminCities']['id']]);

        if ($cookies->has('SUBDOMAINID')) {
            $query->andWhere(['business."idCity"' => $cookies->get('SUBDOMAINID')]);
        }
        
        return $query;
    }
}
