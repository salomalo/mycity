<?php
namespace office\models\search;

use common\models\search\WorkVacantion as WorkVacantionSearch;
use yii;

class WorkVacantion extends WorkVacantionSearch
{
    protected function getModelQuery()
    {
        return parent::getModelQuery()->andWhere(['idUser' => Yii::$app->user->id]);
    }
}
