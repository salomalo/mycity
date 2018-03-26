<?php
namespace office\models\search;

use common\models\search\Action as ActionSearch;
use yii;

class Action extends ActionSearch
{
    protected function getModelQuery()
    {
        return parent::getModelQuery()->andWhere(['business."idUser"' => Yii::$app->user->id]);
    }
}
