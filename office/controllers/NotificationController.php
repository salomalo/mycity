<?php

namespace office\controllers;

use common\models\Notification;

class NotificationController extends DefaultController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return $behaviors;
    }

    public function actionView($id){
        $notification = Notification::findOne($id);

        $notification->status = Notification::STATUS_VISITED;
        $notification->save();

        $this->redirect($notification->link);
    }
}