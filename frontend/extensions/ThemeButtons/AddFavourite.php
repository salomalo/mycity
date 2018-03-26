<?php

namespace frontend\extensions\ThemeButtons;

use common\models\Favorite;
use yii\base\Widget;
use yii;
use yii\helpers\Url;

class AddFavourite extends Widget
{
    public $id;
    public $type;
    public $url;
    public $template = 'favorite';

    public function init()
    {
        $this->url = $this->url ? $this->url : Url::to(['/site/add-favorite']);
    }

    public function run()
    {
        if (Yii::$app->user->isGuest) {
            return $this->render('favorite/' . $this->template . '_auth');
        }

        $favorite = Favorite::find()->where([
            'user_id' => Yii::$app->user->id,
            'object_type' => $this->type,
            'object_id' => $this->id
        ])->count();

        return $this->render('favorite/' . $this->template, [
            'id' => $this->id,
            'url' => $this->url,
            'type' => $this->type,
            'isMarked' => !!$favorite,
        ]);
    }
}
