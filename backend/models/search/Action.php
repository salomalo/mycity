<?php
namespace backend\models\search;

use common\models\search\Action as ActionSearch;
use yii;

class Action extends ActionSearch
{
    protected function getModelQuery()
    {
        $cookies = Yii::$app->request->cookies;

        $query = parent::getModelQuery()->with(['category', 'companyName'])
            ->andWhere(['business."idCity"' => Yii::$app->params['adminCities']['id']]);

        if ($cookies->has('SUBDOMAINID')) {
            $query->andWhere(['business."idCity"' => $cookies->get('SUBDOMAINID')]);
        }

        return $query;
    }
}
