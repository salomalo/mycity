<?php

namespace frontend\extensions\StanzaLatestBlog;

use common\models\Post;
use yii\base\Widget;

class StanzaLatestBlog extends Widget
{
    public $businessModel;
    public $limit = 4;
    public $template = 'index';

    public function run(){
        $models = Post::find()
            ->where(['business_id' => $this->businessModel->id])
            ->orderBy(['id' => SORT_ASC])
            ->limit($this->limit)
            ->all();

        if ($models) {
            return $this->render($this->template, [
                'models' => $models,
                'business' => $this->businessModel,
            ]);
        }
    }
}