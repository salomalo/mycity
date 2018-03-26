<?php
namespace office\models\search;

use common\models\search\Business as BusinessSearch;
use yii;

class Business extends BusinessSearch
{
    protected function getModelQuery()
    {
        return parent::getModelQuery()->andWhere(['idUser' => Yii::$app->user->id]);
    }
}
