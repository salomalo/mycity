<?php

namespace frontend\extensions\UserProfileMenu;

use yii;
use yii\base\Widget;
use yii\widgets\Menu;

class UserProfileMenu extends Widget
{
    public function run()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->render('view');
        }
    }
}
