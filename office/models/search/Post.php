<?php
namespace office\models\search;

use common\models\search\Post as PostSearch;
use yii;

class Post extends PostSearch
{
    protected function getModelQuery()
    {
        return parent::getModelQuery()->andWhere(['idUser' => Yii::$app->user->id]);
    }
}
