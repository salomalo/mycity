<?php
namespace office\models\search;

use common\models\search\WorkResume as WorkResumeSearch;
use yii;

class WorkResume extends WorkResumeSearch
{
    protected function getModelQuery()
    {
        return parent::getModelQuery()->andWhere(['idUser' => Yii::$app->user->id]);
    }
}
