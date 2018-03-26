<?php

namespace office\controllers;

use common\models\Ads;
use common\models\Notification;
use common\models\Orders;
use common\models\PaymentType;
use common\models\Profile;
use common\models\User;
use common\models\UserPaymentType;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use common\models\search\Orders as OrderSearch;
use common\models\search\OrdersAds as OrderSearchAds;
use common\models\OrdersAds;
use common\models\Business;
use yii\web\HttpException;

/**
 * Description of OrderController
 *
 * @author dima
 */
class OrderController extends DefaultController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new OrderSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),true);
        
        $dataProvider->query->orderBy('id DESC');
        $dataProvider->query->andwhere(['idSeller' => Yii::$app->user->id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionView($id)
    {
        //если заказ не наш, то 404
        $order = Orders::findOne($id);
        if ($order->idSeller != Yii::$app->user->id){
            throw new HttpException(404);
        }

        
        $searchModel = new OrderSearchAds();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),true);
        
        $dataProvider->query->orderBy('id DESC');
        $dataProvider->query->andwhere(['pid' => $id]);

        //поиск заказов по моих предприятих пока закоментил
//        $arrId = Business::find()->select('id')->where(['idUser' => Yii::$app->user->id]);
//        $dataProvider->query->andWhere(['idBusiness' => $arrId]);

        $order = Orders::findOne($id);

        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'order' => $order,
        ]);
    }
    
    public function actionUpdate($id)
    {
        $model = OrdersAds::findOne($id);
        
        if ($model->load(Yii::$app->request->post())) {
            
            if($model->save()){
                //$this->sendEmailUser($model->order->user->email, $model->ads->title, $model->status);
                return $this->redirect(['view', 'id' => $model->order->id]);
            }
            
        } 
            
        return $this->render('update', [
                'model' => $model,
            ]);
    }

    public function actionConfirmOrder($id)
    {
        $order = Orders::findOne($id);

        if ($order->load(Yii::$app->request->post())) {
            $content = array();

            $content['fio'] = Profile::findOne($order->idUser)->name;
            $content['userName'] = User::findOne($order->idUser)->username;
            $content['orderNumber'] = $order->id;
            $content['orderData'] = date('Y-m-d H:i:s', (integer)$order->dateCreate);

            $orders_ads = OrdersAds::find()->where(['pid' => $order->id])->all();
            foreach ($orders_ads as $orders_ad){
                $content['ads'][$orders_ad->countAds][] = Ads::find()->where(['_id' => $orders_ad->idAds])->one();
            }

            $content['paymentType'] = ($order->paymentType != '' && isset($order->payment->paymentType->title)) ? $order->payment->paymentType->title : 'Undefined';
            $content['paymentAccount'] = ($order->paymentType != '' && isset($order->payment->description)) ? $order->payment->description : 'Undefined';

            $content['delivery'] = $order->delivery;
            $content['buyerUsername'] = User::findOne($order->idSeller)->username;
            $sellerProfile = Profile::findOne(['user_id' => $order->idSeller]);
            $content['buyerFio'] = $sellerProfile->name;
            $content['buyerPhone'] = $sellerProfile->phone;
            $content['buyerAddress'] = $sellerProfile->address;

            $content['listPaymentSellerType'] = UserPaymentType::find()->where(['user_id' => Yii::$app->user->id])->all();

            if ($order->status == Orders::STATUS_CONFIR){
                $this->sendEmailUser($order->user->email, $content);
            }

            if ($order->save()) {
                //оповещение в офис юзеру, что изменился статус заказа
                $notification = new Notification();
                $notification->sender_id = $order->idUser;
                $notification->status = Notification::STATUS_NEW;
                $notification->title = 'У заказа ' . $order->id . ' изменился статус на ' . Orders::$statusList[$order->status];
                $notification->link = Url::to(['/my-order/view', 'id' => $order->id]);

                $notification->save();

                return $this->redirect(['view', 'id' => $order->id]);
            }
        } else {
            return $this->render('confirm', [
                'model' => $order,
            ]);
        }
    }

    public function actionCancelOrder($id)
    {
        $order = Orders::findOne($id);
        $order->status = Orders::STATUS_CANCEL;
        $order->save();

        $user = User::findOne($order->idUser);

        //оповещение в офис юзеру, что изменился статус заказа
        $notification = new Notification();
        $notification->sender_id = $order->idUser;
        $notification->status = Notification::STATUS_NEW;
        $notification->title = 'Заказ ' . $order->id . ' Отменен';
        $notification->link = Url::to(['/my-order/view', 'id' => $order->id]);

        $notification->save();

        if (isset($user->email)){
            $content = array();

            $content['fio'] = Profile::findOne($order->idUser)->name;
            $content['userName'] = User::findOne($order->idUser)->username;

            $content['orderNumber'] = $order->id;
            $content['orderData'] = date('Y-m-d H:i:s', (integer)$order->dateCreate);

            $content['buyerUsername'] = User::findOne($order->idSeller)->username;
            $sellerProfile = Profile::findOne(['user_id' => $order->idSeller]);
            $content['buyerFio'] = $sellerProfile->name;
            $content['buyerPhone'] = $sellerProfile->phone;
            $content['buyerAddress'] = $sellerProfile->address;

            $this->sendCancelEmailUser($user->email, $content);
        }

        return $this->redirect(['/order/view', 'id' => $id]);
    }

    public function sendCancelEmailUser($email, $content)
    {
        return Yii::$app->mailer->compose(
            ['html' => 'send-user-cancel-order'],
            ['content' => $content]
        )
            ->setTo($email)
            ->setFrom([Yii::$app->params['adminEmail'] => 'CityLife'])
            ->setSubject('Заказ отменён продавцом')
            ->send();

    }
    
    public function sendEmailUser($email, $content)
    {
        return Yii::$app->mailer->compose(
            ['html' => 'send-user-order'],
            ['content' => $content]
        )
        ->setTo($email)
        ->setFrom([Yii::$app->params['adminEmail'] => 'CityLife'])
        ->setSubject('Статус заказа')
//            ->setTextBody($this->body)
        ->send();
       
    }
}
