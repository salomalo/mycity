<?php
namespace backend\models\search;

use common\models\search\ScheduleKino as ScheduleKinoSearch;
use yii;

class ScheduleKino extends ScheduleKinoSearch
{
    protected function getModelQuery()
    {
        return parent::getModelQuery()->andWhere(['idCity' => Yii::$app->params['adminCities']['id']]);
    }
}
