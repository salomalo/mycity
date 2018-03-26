<?php
namespace office\models\search;

use common\models\search\Comment as CommentSearch;
use yii;

class Comment extends CommentSearch
{
    protected function getModelQuery()
    {
        return parent::getModelQuery()->andWhere(['idUser' => Yii::$app->user->id]);
    }
}
