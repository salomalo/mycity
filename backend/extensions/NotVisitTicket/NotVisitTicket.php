<?php
namespace backend\extensions\NotVisitTicket;

use backend\models\Admin;
use common\models\Ticket;
use Yii;
use yii\base\Widget;

class NotVisitTicket extends Widget
{
    public function run()
    {
        $result = 0;
        $admin = Yii::$app->user->identity;
        $cookies = Yii::$app->request->cookies;

        if ($admin->level === Admin::LEVEL_SUPER_ADMIN) {
            $result = Ticket::find()->where(['status' => Ticket::STATUS_QUESTION])->count();
        } elseif ($admin->citiesId) {
            $result = Ticket::find()
                ->where(['status' => Ticket::STATUS_QUESTION])
                ->andWhere(['idCity' => $admin->citiesId])
                ->count();
        }
        return $result;
    }
}