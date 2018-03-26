<?php

namespace office\extensions\Notification;

use common\models\Notification as NotificationModel;
use common\models\QuestionConversation;
use Yii;
use yii\web\View;

class Notification extends \yii\base\Widget
{
    public $title;
    public $className;
    public $attribute;

    public function run()
    {
        $this->runJS();
        $userId = Yii::$app->user->id;
        $count = QuestionConversation::find()->where([
            'status' => QuestionConversation::STATUS_NEW,
            'owner_id' => $userId,
            'object_type' => array_keys(QuestionConversation::$question_types),
        ])
            ->orWhere([
                'status' => QuestionConversation::STATUS_REPLIED,
                'user_id' => $userId,
                'object_type' => array_keys(QuestionConversation::$support_types),
            ])->count();

        $notification = NotificationModel::find()->where([
            'status' => NotificationModel::STATUS_NEW,
            'sender_id' => $userId,
        ])->count();

        $count += $notification;
        return $this->render('notification', [
            'count' =>$count,
        ]);
    }

    private function runJS()
    {
        $userId = Yii::$app->user->id;
        /**@var NotificationModel[] $notes*/
        $notes = NotificationModel::find()->where('status != :status AND type_js != :type_js AND sender_id = :sender_id',[
            ':type_js' => NotificationModel::TYPE_JS_NONE,
            ':status' => NotificationModel::STATUS_VISITED,
            ':sender_id' => $userId,
        ])->all();
        foreach ($notes as $note) {
            $js = $this->getJSbyType($note->type_js);
            if ($js) {
                $this->view->registerJs($js,View::POS_END);
                $note->type_js = NotificationModel::TYPE_JS_NONE;
                $note->save();
            }
        }
    }

    private function getJSbyType($type)
    {
        switch ($type) {
            case NotificationModel::TYPE_JS_PAYMENT_RECIEVE:
                return "ga('send', 'event', 'payment-form', 'send');";
                break;
        }
        return '';
    }
}