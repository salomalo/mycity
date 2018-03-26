<?php
namespace office\models\search;

use common\models\search\Afisha as AfishaSearch;
use yii;

class Afisha extends AfishaSearch
{
    protected function getModelQuery()
    {
        return parent::getModelQuery()->andWhere(['business."idUser"' => Yii::$app->user->id]);
    }
}
