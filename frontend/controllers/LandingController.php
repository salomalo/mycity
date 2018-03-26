<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 12.10.2016
 * Time: 15:47
 */

namespace frontend\controllers;

use common\models\Lead;
use common\models\Orders;
use common\models\Subscription;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class LandingController extends Controller
{
    public function actionIndex()
    {
        $this->layout = 'landing';
        if (Yii::$app->request->city) {
            $url = 'http://' . Yii::$app->params['appFrontend'] . Url::current();
            return $this->redirect($url, 301);
        }
        return $this->render('index');
    }

    public function actionCallback()
    {
        if (Yii::$app->request->isAjax) {
            //send mail
            $this->sendEmailAdmin('3dmaxpayne@gmail.com', Yii::$app->request->post('phone'));
        }
    }

    public function sendEmailAdmin($email, $phone)
    {
        return Yii::$app->mailer->compose(['html' => 'send-phone-admin'], ['phone' => $phone])
            ->setFrom([Yii::$app->modules['user']->mailer['sender'] => 'CityLife'])->setTo($email)
            ->setSubject('Нужно перезвонить')
            ->send();
    }
    
    public function actionSubscribe()
    {
        if (!Yii::$app->request->isAjax) {
            throw new HttpException(404);
        }
        
        $subscription = new Subscription();
        $subscription->email = Yii::$app->request->post('email');
        $subscription->name = Yii::$app->request->post('name');
        $subscription->status = Subscription::STATUS_ACTIVE;

        if (!$subscription->save()) {
            throw new HttpException(500);
        }
    }

    public function actionOrderConsultation($utm_source = null, $utm_campaign= null)
    {
        /** @var Lead $model */
        $model = Yii::createObject(Lead::className());

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ((isset($_POST['Lead'])) and ($form = $_POST['Lead'])) {
            $fields = ['name', 'phone', 'description'];
            foreach ($fields as $field) {
                $model->{$field} = !empty($form[$field]) ? $form[$field] : null;
            }

            $model->utm_source = $utm_source;
            $model->utm_campaign = $utm_campaign;
            $model->status = Lead::STATUS_NEW;

            if ($model->save()){
                $this->sendConsultationEmail('3dmaxpayne@gmail.com', $model);
            }

                Yii::$app->session->setFlash('landingConsultation', $this->renderPartial('consultation_flash_massage'));
//
                return $this->redirect(['/landing/index']);
        }

        return $this->redirect(['/landing/index']);
    }

    /**
     * @param $email string
     * @param $model Lead
     */
    public function sendConsultationEmail($email, $model)
    {
        Yii::$app->mailer->compose(['html' => 'send-consultation-admin'], ['name' => $model->name, 'phone' => $model->phone, 'description' => $model->description])
            ->setFrom([Yii::$app->modules['user']->mailer['sender'] => 'CityLife'])->setTo($email)
            ->setSubject('Заказ консультации')
            ->send();
    }
}
