<?php
namespace office\extensions\SupportChat;

use yii\base\Widget;
use yii;

class SupportChat extends Widget
{
    public function init()
    {
        SupportChatAssets::register($this->view);
    }

    public function run()
    {
        if (Yii::$app->user->identity) {
            return $this->render('main');
        }
        return null;
    }
}
