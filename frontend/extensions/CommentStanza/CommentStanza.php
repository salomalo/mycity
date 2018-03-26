<?php

namespace frontend\extensions\CommentStanza;

use InvalidArgumentException;
use Yii;
use yii\base\Widget;
use common\models\Comment;

class CommentStanza extends  Widget
{
    public $id;
    public $mongo = false;
    public $type;
    public $limit = 3;
    public $template = 'list';

    public function run()
    {
        $comments = Comment::find();
        if (!$this->mongo) {
            $comments->where(['type' => $this->type, 'pid' => $this->id, 'parentId' => null]);
        } else {
            $comments->where(['type' => $this->type, 'pidMongo' => $this->id, 'parentId' => null]);
        }
        $comments = $comments->orderBy('id DESC')->all();

        $model = new Comment();

        echo $this->render($this->template, [
            'comments' => $comments,
            'id' => $this->id,
            'type' => $this->type,
            'mongo' => $this->mongo,
            'model' => $model,
        ]);
    }
}