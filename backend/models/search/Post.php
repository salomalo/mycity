<?php
namespace backend\models\search;

use common\models\search\Post as PostSearch;
use yii;

class Post extends PostSearch
{
    protected function getModelQuery()
    {
        return parent::getModelQuery()->andWhere(['idCity' => Yii::$app->params['adminCities']['id']])->orWhere(['idCity' => null]);
    }
}
