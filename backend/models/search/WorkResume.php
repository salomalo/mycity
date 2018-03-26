<?php
namespace backend\models\search;

use common\models\search\WorkResume as WorkResumeSearch;
use yii;

class WorkResume extends WorkResumeSearch
{
    protected function getModelQuery()
    {
        return parent::getModelQuery()->andWhere(['idCity' => Yii::$app->params['adminCities']['id']]);
    }
}
