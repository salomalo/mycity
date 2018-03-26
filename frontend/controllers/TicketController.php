<?php

namespace frontend\controllers;

use common\models\Ticket;
use common\models\TicketHistory;
use frontend\models\TicketForm;
use Yii;
use yii\web\Controller;

/**
 * Description of TicketController
 *
 * @author dima
 */
class TicketController extends Controller
{
    public $layout = 'site';
    public $breadcrumbs = [];

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $breadcrumbs = [
            ['label' => Yii::t('app', 'Customer_Support_Center')],
        ];
        $this->breadcrumbs = $breadcrumbs;

        $model = new TicketForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $ticket = new Ticket();
            $ticket->idUser = $model->idUser;
            $ticket->title = $model->title;
            $ticket->email = $model->email;
            $ticket->body = $model->body;
            $ticket->status = Ticket::STATUS_QUESTION;
            $ticket->pid = 0;
            $ticket->type = $model->type;
//            $ticket->save();

            if ($ticket->save()) {

                $ticketHistory = new TicketHistory();
                $ticketHistory->idTicket = $ticket->id;
                $ticketHistory->body = $ticket->body;
                $ticketHistory->dateCreate = $ticket->dateCreate;
                $ticketHistory->save();

                Yii::$app->session->setFlash('success', 'Ваш вопрос отправлен.');
            } else {
                Yii::$app->session->setFlash('error', 'Ваш вопрос не отправлен.');
            }

            return $this->refresh();
        }

        return $this->render('index', ['model' => $model]);

    }
}
