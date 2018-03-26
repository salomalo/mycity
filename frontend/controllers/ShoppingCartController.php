<?php
namespace frontend\controllers;

use common\models\Notification;
use common\models\PaymentType;
use common\models\Profile;
use common\models\UserPaymentType;
use frontend\components\traits\BusinessTrait;
use frontend\helpers\SeoHelper;
use frontend\models\ShoppingCartForm;
use frontend\models\SignupForm;
use yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\helpers\Json;
use common\models\Ads;
use common\models\Orders;
use common\models\OrdersAds;
use common\models\Business;
use common\models\User;
use yii\web\Cookie;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Description of ShoppingCart
 *
 * @author dima
 */
class ShoppingCartController extends Controller
{
    use BusinessTrait;

    public $alias_category = '';
    public $id_category = null;
    public $breadcrumbs = [['label' => 'Моя корзина']];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['do-order', 'view', 'change-item'],
                'rules' => [
                    [
                        'actions' => ['do-order', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['change-item'],
                        'allow' => true,
                        'verbs' => ['POST'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return ['error' => ['class' => 'yii\web\ErrorAction']];
    }

    public function actionIndex($alias = null)
    {
        if ($alias) {
            $url = explode('-', $alias, 2);
            $url[0] = (int)$url[0];

            if (!$url[0]) {
                throw new HttpException(404);
            }

            $this->businessModel = Business::find()->where(['id' => (int)$url[0]])->one();
            if (!$this->businessModel){
                throw new HttpException(404);
            }
            $this->initTemplate();

            $alias = "{$this->businessModel->id}-{$this->businessModel->url}";
            $this->breadcrumbs = [['label' => $this->businessModel->title, 'url' => ['/business/view', 'alias' => $alias]]];
            $this->breadcrumbs[] = ['label' => 'Моя корзина'];
        }

        $doOrderModel = new ShoppingCartForm();

        //если аякс запрос то делаем валидацию модели
        if (Yii::$app->request->isAjax && $doOrderModel->load(Yii::$app->request->post())) {
            if (!$doOrderModel->validate()){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($doOrderModel);
            }
        } else {
            if ($doOrderModel->load(Yii::$app->request->post())) {

                //если залогиненый то
                if (Yii::$app->user->identity) {
                    $user = User::findOne(Yii::$app->user->identity->id);
                    $user->city_id = $doOrderModel->idCity;
                    $user->update();

                    $profile = Profile::findOne(Yii::$app->user->identity->id);
                    if (!$profile->phone || (isset($profile->phone) && $profile->phone == '')){
                        $profile->phone = $doOrderModel->phone;

                        $profile->save();
                    }

                    $getCookies = Yii::$app->request->cookies;
                    $value = $getCookies->get('shopping-cart');
                    $newArr = Json::decode($value);

                    $this->addOrders($newArr, $doOrderModel);

                    $cookie = new Cookie([
                        'domain' => Yii::$app->session->cookieParams['domain'],
                        'name' => 'shopping-cart',
                    ]);
                    Yii::$app->response->cookies->remove($cookie);

                    /** @var $order Orders*/
                    $order = Orders::find()->orderBy(['id' => SORT_DESC])->one();
                    if ($this->businessModel){
                        $alias = "{$this->businessModel->id}-{$this->businessModel->url}";
                        return $this->redirect(['/business/' .$alias . '/shopping-cart/view', 'id' => $order->id]);
                    } else {
                        return $this->redirect(['view', 'id' => $order->id]);
                    }

                } else {
                    // если новый юзер то регаем и потом добавляем заказы
                    $model = new SignupForm();
                    $model->username = $doOrderModel->username;
                    $model->apply = $doOrderModel->apply;
                    $model->email = $doOrderModel->email;
                    $model->password = $doOrderModel->password;
                    $model->password2 = $doOrderModel->password;

                    if ($user = $model->signup()) {
                        $user->phone = $doOrderModel->phone;
                        $user->save();
                        $user->updateAttributes(['last_activity' => date('Y-m-d H:i:s')]);
                        if (Yii::$app->getUser()->login($user)) {
                            $getCookies = Yii::$app->request->cookies;
                            $value = $getCookies->get('shopping-cart');
                            $newArr = Json::decode($value);

                            $doOrderModel->idUser = Yii::$app->user->identity->id;

                            $this->addOrders($newArr, $doOrderModel);

                            $cookie = new Cookie([
                                'domain' => Yii::$app->session->cookieParams['domain'],
                                'name' => 'shopping-cart',
                            ]);
                            Yii::$app->response->cookies->remove($cookie);

                            /** @var $order Orders*/
                            $order = Orders::find()->orderBy(['id' => SORT_DESC])->one();
                            return $this->render('view', ['id' => $order->id]);
                        }
                    }
                }
            }

            $cookie = Yii::$app->request->cookies->getValue('shopping-cart');
            $models = [];
            $counts = [];

            if ($cookie) {
                $counts = Json::decode($cookie);
                if (is_array($counts)) {
                    $query = Ads::find()->with(['business', 'user']);
                    $query->where(['_id' => array_keys($counts)]);
                    $models = $query->all();
                }
            }
            $orders = [];
            $unknown = [];
            /** @var Ads $model */
            foreach ($models as $model) {
                if ($model->business) {
                    $orders[$model->business->title][] = $model;
                } elseif ($model->user) {
                    $orders[$model->user->username][] = $model;
                } else {
                    $unknown[] = $model;
                }
            }
            if ($unknown) {
                $orders[''] = $unknown;
            }

            $paymentType = PaymentType::find()->all();

            SeoHelper::registerAllMeta($this->view, ['title' => Yii::t('app', 'Shopping cart')]);
            SeoHelper::registerTitle($this->view, Yii::t('app', 'Shopping cart'));

            return $this->render('index', [
                'models' => $orders,
                'counts' => $counts,
                'paymentType' => $paymentType,
                'doOrderModel' => $doOrderModel,
                'business' => $this->businessModel,
            ]);
        }
    }

    /**
     * @param $cookies
     * @param $doOrderModel ShoppingCartForm
     */
    public function addOrders($cookies, $doOrderModel)
    {
        $adsArray = ['business' => [], 'user' => [], 'empty' => []];
        $adsArrayUser = ['business' => [], 'user' => []];
        //групируем заказы по преприятиям, пользователям и те что остались
        foreach ($cookies as $id => $count) {
            $ads = Ads::findOne(['_id' => $id]);
            if ($ads) {
                $adsArrayUser['business'][$count][] = $ads;

                if (isset($ads->idBusiness) and $ads->idBusiness and $ads->business) {
                    $adsArray['business'][$ads->idBusiness][] = $ads;
                } elseif (isset($ads->idUser) and $ads->idUser and $ads->user) {
                    $adsArray['user'][$ads->idUser][] = $ads;
                } else {
                    $adsArray['empty'][0][] = $ads;
                }
            }
        }

        //делаем заказы по каждому предприятию
        foreach ($adsArray['business'] as $key => $ads){
            $seller = Business::findOne($key);

            if (($sellerName = isset(User::findOne($seller->idUser)->username))){
                $sellerName = User::findOne($seller->idUser)->username;
                $email = $seller->email ? $seller->email : ($seller->user ? $seller->user->email : null);

                $this->doSubOrder($ads, $doOrderModel, $cookies, $seller->idUser, $sellerName, $email);
            }
        }

        //делаем заказы по каждому юзеру
        foreach ($adsArray['user'] as $key => $ads){
            $seller = User::findOne($key);
            $sellerName = $seller->username;
            $email = $seller->email ? $seller->email : null;

            $this->doSubOrder($ads, $doOrderModel, $cookies, $key, $sellerName, $email);
        }

        //делаем заказы по пустых для админа
        foreach ($adsArray['empty'] as $key => $ads){
            $seller = User::findOne(1);
            $sellerName = $seller->username;
            $email = $seller->email ? $seller->email : null;

            $this->doSubOrder($ads, $doOrderModel, $cookies, 1, $sellerName, $email);
        }

        //отсылаем письмо юзеру
        $userData = array();
        $user = User::findOne(Yii::$app->user->identity->id);

        $userData['ads'] = $adsArrayUser['business'];
        $userData['userName'] = $user->username;
        $userData['paymentType'] = $doOrderModel->getPaymentDescription();
        $userData['delivery'] = $doOrderModel->delivery;
        $userData['orderData'] = date('Y-m-d H:i:s');
        $userData['fio'] = $doOrderModel->fio;
        $userData['sellerDelivery'] = $doOrderModel->delivery;
        $userData['sellerOffice'] = $doOrderModel->office;

        if (Yii::$app->user->identity->email) {
            $this->sendEmailUser($userData);
        }
    }

    /**
     * @param $ads
     * @param $doOrderModel ShoppingCartForm
     * @param $cookies
     * @param $idSeller
     * @param $sellerName
     * @param $email
     */
    public function doSubOrder($ads, $doOrderModel, $cookies, $idSeller, $sellerName, $email)
    {
        $user = User::findOne(Yii::$app->user->identity->id);

        $modelOrder = new Orders();
        $modelOrder->idUser = $doOrderModel->idUser;
        $modelOrder->idCity = $doOrderModel->idCity;
        $modelOrder->phone = $doOrderModel->phone;
        $modelOrder->fio = $doOrderModel->fio;
        $modelOrder->address = $doOrderModel->address;
        $modelOrder->paymentType = $doOrderModel->paymentType;
        $modelOrder->delivery = $doOrderModel->delivery;
        $modelOrder->office = $doOrderModel->office;
        $modelOrder->idSeller = $idSeller;
        $modelOrder->status = Orders::STATUS_NEW;

        $modelOrder->save();

        //оповещение в офис юзеру, что он сделал заказ
        $notification = new Notification();
        $notification->sender_id = Yii::$app->user->identity->id;
        $notification->status = Notification::STATUS_NEW;
        $notification->title = 'Вы сделали новый заказ ' . $modelOrder->id;
        $notification->link = Url::to(['/my-order/view', 'id' => $modelOrder->id]);

        $notification->save();

        //оповещение в офис продавцу, что у него сделали заказ
        $notification = new Notification();
        $notification->sender_id = $idSeller;
        $notification->status = Notification::STATUS_NEW;
        $notification->title = 'У Вас новый заказ ' . $modelOrder->id;
        $notification->link = Url::to(['/order/view', 'id' => $modelOrder->id]);

        $notification->save();

        foreach ($ads as $ad){
            $model = new OrdersAds();
            $model->pid = $modelOrder->id;
            $model->idAds = (string)$ad->_id;
            $model->countAds = $cookies[(string)$ad->_id];
            $model->idBusiness = isset($ad->idBusiness) ? $ad->idBusiness : 0;
            $model->idUser = $modelOrder->idSeller;
            $model->status = OrdersAds::STATUS_ONCONFIRMATION;

            $model->save();
        }

        $newBusinessContent = array();
        $newBusinessContent['paymentType'] = $doOrderModel->getPaymentDescription();
        $newBusinessContent['delivery'] = $modelOrder->delivery;
        $newBusinessContent['orderData'] = date('Y-m-d H:i:s',$modelOrder->dateCreate);
        $newBusinessContent['orderNumber'] = $modelOrder->id;
        $newBusinessContent['buyerFio'] = $modelOrder->fio;
        $newBusinessContent['buyerPhone'] = $modelOrder->phone;
        $newBusinessContent['buyerDelivery'] = $modelOrder->delivery;
        $newBusinessContent['buyerOffice'] = $modelOrder->office;
        $newBusinessContent['buyerAddress'] = $modelOrder->address;
        $newBusinessContent['buyerUsername'] = $user->username;

        $newBusinessContent['sellerName'] = $sellerName;
        $newBusinessContent['ads'] = $ads;
        $newBusinessContent['cookies'] = $cookies;

        if ($email) {
            $this->sendEmailBusiness($email, $modelOrder, $newBusinessContent);
        }
    }

    public function actionAddShoppingCart()
    {
        $getCookies = Yii::$app->request->cookies;

        $id = $_POST['id'];
        $res = null;
        $count = 0;

        //Если куки пустые то добавляет  товар
        if (!$getCookies->get('shopping-cart')) {
            $this->addCookies([$id => 1]);
            $count = 1;
            $res = 'add';
        } else {
            $value = $getCookies->get('shopping-cart');
            $newArr = Json::decode($value);

            //если есть в корзине то удаляем, повторное нажатие на кнопку
            if (array_key_exists($id, $newArr)) {
                //раньше тут добавляли 1 к количеству итемов
                //сейчас добавление идет через метод  actionChangeItem
//                $newArr[$id] += 1;
//                $res = "$res ({$newArr[$id]})";
//                $this->addCookies($newArr);

                if (isset($newArr[$id])) {
                    unset($newArr[$id]);
                }

                if ($newArr) {
                    foreach ($newArr as $key => $value){
                        $count += $value;
                    }
                    $this->addCookies($newArr);
                } else {
                    $cookie = new Cookie([
                        'domain' => Yii::$app->session->cookieParams['domain'],
                        'name' => 'shopping-cart',
                    ]);

                    Yii::$app->response->cookies->remove($cookie);
                }

                $res = 'remove';
            } else {
                $newArr[$id] = 1;
                $this->addCookies($newArr);

                foreach ($newArr as $key => $value){
                    $count += $value;
                }
                $res = 'add';
            }
        }

        if (isset($_POST['redirect'])){
            return $this->redirect('index');
        } else {
            echo Json::encode(['result' => $res, 'count' => $count]);
        }

    }

    public function addCookies($arr)
    {
        $cookies = Yii::$app->getResponse()->getCookies();

        $cookie = new Cookie([
            'domain' => Yii::$app->session->cookieParams['domain'],
            'name' => 'shopping-cart',
            'value' => Json::encode($arr),
            'expire' => time() + 86400 * 365,
        ]);

        $cookies->add($cookie);
    }

    public function actionUpdateShoppingCart()
    {
        $user = Yii::$app->user->identity;
        $id = (string)Yii::$app->request->post('id');
        $count = (int)Yii::$app->request->post('count');
        $cookie = Yii::$app->request->cookies->getValue('shopping-cart');
        $orderArray = [];

        if ($user and Yii::$app->request->isAjax and $id and $count) {
            if ($cookie) {
                $orderArray = Json::decode($cookie);
            }
            $orderArray[$id] = $count;
        }

        $this->addCookies($orderArray);

        return Json::encode($count);
    }

    public function actionDoOrder()
    {
        $model = new Orders();

        if ($model->load(Yii::$app->request->post())) {
            $profile = Profile::findOne(Yii::$app->user->identity->id);
            $profile->address = $model->address;
            $profile->fio = $model->fio;
            $profile->phone = $model->phone;
            $profile->country_id = $model->idCity;

            $profile->update();

            if ($model->save()) {
                $this->saveOrderAds($model);

                Yii::$app->response->cookies->remove('shopping-cart');

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $user = User::find()->select(['id', 'username'])->where(['id' => Yii::$app->user->identity->id])->one();

        return $this->render('do-order', [
            'model' => $model,
            'user' => $user
        ]);
    }

    public function actionView($id, $alias = null)
    {
        if ($alias) {
            $url = explode('-', $alias, 2);
            $url[0] = (int)$url[0];

            if (!$url[0]) {
                throw new HttpException(404);
            }

            $this->businessModel = Business::find()->where(['id' => (int)$url[0]])->one();
            $this->initTemplate();

            $alias = "{$this->businessModel->id}-{$this->businessModel->url}";
            $this->breadcrumbs = [['label' => $this->businessModel->title, 'url' => ['/business/view', 'alias' => $alias]]];
            $this->breadcrumbs[] = ['label' => 'Моя корзина'];
        }

        return $this->render('view', [
            'model' => Orders::findOne($id),
        ]);
    }

    /**
     * @param $modelOrder \common\models\Orders
     */
    private function saveOrderAds($modelOrder)
    {
        $getCookies = Yii::$app->request->cookies;

        if ($getCookies->get('shopping-cart')) {
            $value = $getCookies->get('shopping-cart');
            $newArr = Json::decode($value);

            foreach ($newArr as $key => $value) {
                $ads = Ads::findOne(['_id' => (string)$key]);

                $model = new OrdersAds();
                $model->pid = $modelOrder->id;
                $model->idAds = $key;
                $model->countAds = $value;
                $model->idBusiness = $ads->idBusiness;
                $model->idUser = $ads->idUser;
                $model->status = OrdersAds::STATUS_ONCONFIRMATION;

                $model->save();
            }

            $this->groupByBusiness($newArr, $modelOrder);
        }
    }

    public function actionClearShoppingCart(){
        $cookies = Yii::$app->response->cookies;
        $cookie = new Cookie([
            'domain' => Yii::$app->session->cookieParams['domain'],
            'name' => 'shopping-cart',
        ]);
        $cookies->remove($cookie);

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDelete($id)
    {
        $cookie = Yii::$app->request->cookies->getValue('shopping-cart');

        if ($cookie) {
            $orderArray = Json::decode($cookie);
            if (isset($orderArray[$id])) {
                unset($orderArray[$id]);
            }

            if ($orderArray) {
                $this->addCookies($orderArray);
            } else {
                $cookie = new Cookie([
                    'domain' => Yii::$app->session->cookieParams['domain'],
                    'name' => 'shopping-cart',
                ]);

                Yii::$app->response->cookies->remove($cookie);
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Count number items in shopping cart
     *
     * @return int
     */
    public static function getNumbetItem(){
        $getCookies = Yii::$app->request->cookies;
        $value = $getCookies->get('shopping-cart');

        $newArr = Json::decode($value);

        $count = 0;
        if (isset($newArr)) {
            foreach ($newArr as $key => $value) {
                $count += $value;
            }
        }

        return $count;
    }

    public static function isInBasket($item_id){
        $getCookies = Yii::$app->request->cookies;
        $value = $getCookies->get('shopping-cart');

        $newArr = Json::decode($value);

        if (isset($newArr)) {
            foreach ($newArr as $key => $value) {
                if ($item_id == $key){
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Change number item in the basket use ajax request
     *
     * @return array, @totalPrice sum of all items in the basket
     */
    public function actionChangeItem()
    {
        $getCookies = Yii::$app->request->cookies;
        $total = 0;
        $countItem = 0;

        if(Yii::$app->request->isAjax){
            $data = Yii::$app->request->post();
            $idProd = $data['idItem'];
            $numberProd = $data['numberItem'];

            $value = $getCookies->get('shopping-cart');
            $newArr = Json::decode($value);

            if (array_key_exists($idProd, $newArr)) {
                $newArr[$idProd] = $numberProd;
                $this->addCookies($newArr);
            }

            $models = null;
            if (is_array($newArr)) {
                $query = Ads::find()->with(['business', 'user']);
                $query->where(['_id' => array_keys($newArr)]);
                $models = $query->all();
            }

            $totalCount = 0;
            foreach ($models as $model){
                if ($model->discount){
                    $model->price = $model->price * (1 - $model->discount / 100);
                }
                $total += $newArr[(string)$model->_id] * $model->price;
                $totalCount += $newArr[(string)$model->_id];
                if ($model->_id == $idProd){
                    $countItem = $newArr[(string)$model->_id] * $model->price;
                }
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'totalPrice' => $total . ' грн.',
                'countItem' => $countItem . ' грн.',
                'totalCount' => $totalCount,
            ];
        }
    }

    /**
     * @param $cookies
     * @param $modelOrder \common\models\Orders
     */
    public function groupByBusiness($cookies, $modelOrder)
    {
        $user = User::findOne(Yii::$app->user->identity->id);
        $adsArray = ['business' => [], 'user' => []];
        $adsArrayUser = ['business' => [], 'user' => []];
        foreach ($cookies as $id => $count) {
            $ads = Ads::findOne(['_id' => $id]);
            $adsArrayUser['business'][$count][] = $ads;

            if ($ads->idBusiness and $ads->business) {
                $adsArray['business'][$ads->idBusiness][] = $ads;
            } elseif ($ads->idUser and $ads->user) {
                $adsArray['user'][$ads->idUser][] = $ads;
            }
        }

        $businessUserIds = array();
        $newBusinessContent = array();
        $newBusinessContent['paymentType'] = PaymentType::findOne($modelOrder->paymentType)->title;
        $newBusinessContent['delivery'] = $modelOrder->delivery;
        $newBusinessContent['orderData'] = date('Y-m-d H:i:s',$modelOrder->dateCreate);
        $newBusinessContent['orderNumber'] = $modelOrder->id;
        $newBusinessContent['buyerFio'] = $modelOrder->fio;
        $newBusinessContent['buyerPhone'] = $modelOrder->phone;
        $newBusinessContent['buyerDelivery'] = $modelOrder->delivery;
        $newBusinessContent['buyerOffice'] = $modelOrder->office;
        $newBusinessContent['buyerAddress'] = $modelOrder->address;
        $newBusinessContent['buyerUsername'] = $user->username;

        foreach ($adsArray['business'] as $business => $ads) {
            $businessUserIds[] = Business::findOne($business)->idUser;
            /** @var Business $seller */
            $seller = Business::findOne($business);

            $newBusinessContent['sellerName'] = User::findOne($seller->idUser)->username;
            $newBusinessContent['ads'] = $ads;
            $newBusinessContent['cookies'] = $cookies;

            $email = $seller->email ? $seller->email : ($seller->user ? $seller->user->email : null);
            if ($email) {
                $this->sendEmailBusiness($email, $modelOrder, $newBusinessContent);
            }
        }

        $sellerNames = array();
        foreach ($adsArray['user'] as $user => $ads) {
            $businessUserIds[] = $user;
            /** @var User $seller */
            $seller = User::findOne($user);

            $newBusinessContent['sellerName'] = $seller->username;
            $newBusinessContent['ads'] = $ads;
            $newBusinessContent['cookies'] = $cookies;

            $email = $seller->email ? $seller->email : null;
            if ($email) {
                $sellerNames[] = $seller->username;
                $this->sendEmailBusiness($email, $modelOrder, $newBusinessContent);
            }
        }

        $userData = array();

        $userData['ads'] = $adsArrayUser['business'];
        $userData['userName'] = $user->username;
        $userData['paymentType'] = PaymentType::findOne($modelOrder->paymentType)->title;
        $userData['delivery'] = $modelOrder->delivery;
        $userData['orderData'] = date('Y-m-d H:i:s',$modelOrder->dateCreate);
        $userData['orderNumber'] = $modelOrder->id;
        $userData['fio'] = $modelOrder->fio;
        $userData['sellerNames'] = $sellerNames;
        $userData['sellerData'] = Profile::find()->where(['user_id' => $businessUserIds])->all();
        $userData['sellerDelivery'] = $modelOrder->delivery;
        $userData['sellerOffice'] = $modelOrder->office;

        $newUserContent = $userData;

        if (Yii::$app->user->identity->email) {
            $this->sendEmailUser($newUserContent);
        }
    }

    /**
     * @param $email
     * @param $modelOrder Orders
     * @param $content
     * @return mixed
     */
    public function sendEmailBusiness($email, $modelOrder, $content)
    {
        return Yii::$app->mailer->compose(['html' => 'send-firm-order'], [
            'content' => $content,
            'user' => $modelOrder->user ? $modelOrder->user->username : '',
            'address' => $modelOrder->address,
            'paymentType' => $modelOrder->payment ? $modelOrder->payment->paymentType->title : '',
        ])
            ->setFrom([Yii::$app->modules['user']->mailer['sender'] => 'CityLife'])->setTo($email)
            ->setSubject('Новый заказ на CityLife')
            ->send();
    }

    public function sendEmailUser($userContent)
    {
        return Yii::$app->mailer->compose(['html' => 'send-user-order'], ['content' => $userContent])
            ->setTo(Yii::$app->user->identity->email)
            ->setFrom([Yii::$app->modules['user']->mailer['sender'] => 'CityLife'])
            ->setSubject('Новый заказ с CityLife')
            ->send();
    }
}
