<?php

namespace frontend\controllers;

use common\components\LiqPay\LiqPayCurrency;
use common\components\LiqPay\LiqPayStatuses;
use common\components\LiqPay\models\Order;
use common\components\LiqPay\models\Subscribe;
use common\models\Action;
use common\models\Ads;
use common\models\Afisha;
use common\models\Business;
use common\models\City;
use common\models\CityDetail;
use common\models\Favorite;
use common\models\File;
use common\models\Invoice;
use common\models\Lang;
use common\models\LiqpayPayment;
use common\models\Notification;
use common\models\Post;
use common\models\Product;
use common\models\SignupForm;
use common\models\Ticket;
use common\models\User;
use dektrium\user\models\LoginForm;
use frontend\helpers\SeoHelper;
use frontend\models\PasswordResetRequestForm;
use frontend\models\SuggestionsForm;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use common\models\SigninForm;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $breadcrumbs = [];
    private $seo_title_city = '{city} - сайт города. Каталог предприятий, Афиша, Акции, Новости';
    private $seo_description_city = 'CityLife {city} - сайт города. Каталог предприятий, Афиша, Акции, Новости и другая полезная информация на {url}';
    private $seo_keywords_city = '{city} сайт город каталоги афиша новости';
    private $seo_title = 'CityLife - Сеть городских порталов Украины';
    private $seo_description = 'Портал CityLife Украина - первый действительно современный и удобный городской сайт, информация о городе и в городе';
    private $seo_keywords = 'citylife, CityLife, ситилайф, сити лайф, афиша, акции, предприятия, товары, услуги, объявления, поиск';
    
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

    public function behaviors()
    {
        return [
//            [
//                'class' => 'yii\filters\PageCache',
//                'only' => ['index'],
//                'duration' => 60 * 60*24,
//                'variations' => [
//                    Yii::$app->language,
//                    Yii::$app->request->city ? Yii::$app->request->city->id : 0,
//                    Yii::$app->user->isGuest,
//                ],
//            ],
        ];
    }

    public function init()
    {
        $this->seo_title = Yii::t('app', 'seo title');
        $this->seo_description = Yii::t('app', 'seo description');
        $this->seo_keywords = Yii::t('app', 'seo keywords');
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function actionSphinx()
    {
        $this->breadcrumbs = Yii::$app->params['SUBDOMAINTITLE'] ? [['label' => Yii::t('app', 'Home')]] : [];

        if ($city = Yii::$app->request->city) {
            $title = Yii::t('app', 'CityLife index page title {city_ge}', ['city_ge' => $city->title_ge]);
            $desc = $this->seo_description_city;
            $key = Yii::t('app', 'CityLife index page keywords {city} {city_ge}', ['city_ge' => $city->title_ge, 'city' => $city->title]);
            $title_page = $title;
        } else {
            $title = $this->seo_title;
            $desc = $this->seo_description;
            $key = $this->seo_keywords;
            $title_page = $title;
        }
        SeoHelper::registerAllMeta($this->view, ['title' => $title, 'description' => $desc, 'keywords' => $key]);
        SeoHelper::registerOgImage();

        return $this->render('sphinx_index', ['title' => $title_page]);
    }

    public function actionIndex()
    {
        $this->breadcrumbs = Yii::$app->params['SUBDOMAINTITLE'] ? [['label' => Yii::t('app', 'Home')]] : [];

        if ($city = Yii::$app->request->city) {
            $title = Yii::t('app', 'CityLife index page title {city_ge}', ['city_ge' => $city->title_ge]);
            $desc = $this->seo_description_city;
            $key = Yii::t('app', 'CityLife index page keywords {city} {city_ge}', ['city_ge' => $city->title_ge, 'city' => $city->title]);
            $title_page = $title;
        } else {
            $title = $this->seo_title;
            $desc = $this->seo_description;
            $key = $this->seo_keywords;
            $title_page = $title;
        }
        SeoHelper::registerAllMeta($this->view, ['title' => $title, 'description' => $desc, 'keywords' => $key]);
        SeoHelper::registerOgImage();
        
        $this->view->title = $title_page;

        return $this->render('index',[
            'countBusinesses' => Business::find()->city()->count(),
            'countUsers' => User::find()->count(),
            'countAds' => Ads::find()->city()->count(),
            'countAction' => Action::find()->city()->count(),
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            //$post= Yii::$app->request->post();
            \Yii::$app->files->upload($model, 'photoUrl');
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('info', Yii::t('app', 'Forgot_password_ok'));

                return $this->render('message', [
                    'title'  => \Yii::t('user', 'Recovery message sent'),
                    'module' => $this->module,
                ]);
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Forgot_password_error'));
            }
        }

        return $this->render('request_password_reset_token', [
            'model' => $model,
        ]);
    }

    public function actionCity()
    {

        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);

            if ($id) {
                $list = City::find()->where(['idRegion' => $id])->orderBy('title')->all();
            } else {
                $list = City::find()->orderBy('title')->all();
            }
            $selected = '';
            if ($list) {
                foreach ($list as $i => $item) {
                    $out[] = ['id' => $item->id, 'name' => $item->title];
                    if ($i == 0) {
                        $selected = $item->id;
                    }
                }

                return Json::encode(['output' => $out, 'selected' => $selected]);
            }
        }

        if (isset($_POST['City'])) {
            $city = City::find()->select('subdomain')->where(['id' => (int)$_POST['City']['id']])->one();
            if ($city) {
                //$arr = explode('//', \Yii::$app->request->baseUrl);
                $arr = explode('.', $_SERVER['SERVER_NAME']);
                $model = City::find()->select('id')->where(['subdomain' => $arr[0]])->asArray()->one();
                //echo \yii\helpers\BaseVarDumper::dump($model, 10, true); die();
                $url = 'http://';
                if (!$model) {
                    $url .= $city->subdomain . '.' . $_SERVER['SERVER_NAME'];
                } else {
                    $arr[0] = $city->subdomain;
                    foreach ($arr as $key => $item) {
                        $url .= $item;
                        if ($key < ((count($arr)) - 1)) {
                            $url .= '.';
                        }
                    }
                }
                //$url = $arr[0] . '//' . $city->subdomain . '.' . $arr[1]; 
                return \Yii::$app->response->redirect($url . '/' . Lang::getCurrent()->url, 301);
            }
        }

        //echo Json::encode(['output' => '', 'selected'=>'']);
    }

    public function actionGetAjax()
    {
        if (!Yii::$app->request->isAjax) throw new HttpException(404);
        echo file_get_contents($_POST["site"]); // Отправляем запрос и выводим ответ
    }

    public function actionShowmodalLogin()
    {
        if ((!Yii::$app->request->isAjax)) {
            throw new NotFoundHttpException();
        }
        $model = Yii::createObject(SigninForm::className());

        $redirectUrl = null;
        if (!empty($_POST['redirectUrl'])) {
            $redirectUrl = $_POST['redirectUrl'];
        }
        if (isset($_POST['login-form']['login'])) {
            $account = User::find()
                //->where(['email' => $_POST['username']])
                ->andFilterWhere([
                    'or',
                    ['like', 'email', $_POST['login-form']['login']],
                    ['@@', 'email', $_POST['login-form']['login']]
                ])
                ->one();
            $model->login = ($account) ? $account->email : $_POST['login'];
            $model->password = $_POST['login-form']['password'];

            if ($model->validate() && $model->login()) {
                //            return $this->goBack();
                if (!$redirectUrl) {
                    return $this->redirect(Url::previous('actions-redirect'));
                } else {
                    return $this->redirect(Yii::$app->urlManager->createUrl($redirectUrl));
                }
            }

            $model->username = $_POST['username'];
        }

        return $this->renderAjax('login_modal', [
            'model' => $model,
            'redirectUrl' => $redirectUrl
        ]);
    }

    public function actionShowmodalSignup()
    {
        if ((!Yii::$app->request->isAjax)) {
            throw new NotFoundHttpException();
        }
        $model = new SignupForm();

        if (isset($_POST['username'])) {

            $model->username = $_POST['username'];
            $model->email = $_POST['email'];
            $model->password = $_POST['password'];
            $model->password2 = $_POST['password2'];
            $model->apply = $_POST['apply'];

            if ($model->validate()) {
                if ($user = $model->signup()) {
                    if ($model->sendEmail()) {
//                    Yii::$app->getSession()->setFlash('signup_success', $model->email);
                        return $this->renderAjax('singup-activate_modal', [
                            'email' => $model->email
                        ]);
                    } else {
                        Yii::$app->getSession()->setFlash('signup_error', Yii::t('app', 'signup_error'));
                    }
                }
            }

        }

        return $this->renderAjax('signup_modal', [
            'model' => $model,
        ]);
    }

    public function actionShowmodalPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if (isset($_POST['email'])) {
            $model->email = $_POST['email'];

            if ($model->validate()) {
                if ($model->sendEmail()) {
                    Yii::$app->getSession()->setFlash('success', $model->email);
                    return $this->renderAjax('modal_message', ['text' => Yii::t('app', 'Forgot_password_ok')]);
                    //return $this->goHome();
                } else {
                    Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Forgot_password_error'));
                }
            }
        }

        return $this->renderAjax('passwordResetToken_modal', [
            'model' => $model,
        ]);
    }

    public function actionResetSuccess()
    {
        $email = $_POST['email'];
        return $this->renderAjax(($email) ? 'reset-success_modal' : 'reset-error_modal', [
            'email' => $email
        ]);
    }

    public function actionSingupActivate()
    {
        $email = $_POST['email'];
        return $this->renderAjax('singup-activate_modal', [
            'email' => $email
        ]);
    }

    public function actionAccountActivate($key = null)
    {
        if ($key) {
            $user = User::findOne([
                'status' => User::STATUS_SIGNUP,
                'auth_key' => $key,
            ]);

            if ($user) {
                if ((int)((time() - $user->created_at) / 3600 / 24) < 29) {
                    $user->status = User::STATUS_ACTIVE;
                    $user->save(false, ['status']);
                    Yii::$app->getSession()->setFlash('activate_success', $user->email);
                    if (Yii::$app->getUser()->login($user)) {
                        return $this->goHome();
                    }
                } else {
                    $user->delete();
                    Yii::$app->getSession()->setFlash('activate_error', Yii::t('app', 'activate_error'));
                }
            } else {
                Yii::$app->session->setFlash('activate_error', Yii::t('app', 'activate_error'));
            }
        }
        return $this->render('index');
    }

    public function actionActivateResult()
    {
        $email = $_POST['email'];
        return $this->renderAjax(($email) ? 'activate_success_modal' : 'activate_error_modal', [
            'email' => $email,
            'city' => (defined('SUBDOMAINTITLE')) ? SUBDOMAINTITLE : '',
        ]);
    }

    public function actionShowmodalMessageChangeCity()
    {
        return $this->renderAjax('message_change_city_modal', []);
    }

    public function actionShowmodalMessageLoginAuth()
    {
        return $this->renderAjax('message_login_auth_modal', []);
    }

    public function actionShowmodalMessage()
    {
        $text = $_POST['text'];
        return $this->renderAjax('modal_message', ['text' => $text]);
    }

    public function actionShowmodalContact($type)
    {
        return $this->renderAjax('modal_' . $type, ['text' => $type]);
    }

    public function actionShowmodalComplaint()
    {
        $model = new SuggestionsForm();

        if (isset($_POST['text'])) {

            $model->idCity = $_POST['idCity'];
            $model->name = $_POST['name'];
            $model->email = $_POST['email'];
            $model->text = $_POST['text'];

            if ($model->validate()) {
                //            return $this->goBack();
                $ticket = new Ticket();
                $ticket->idUser = isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0;
                $ticket->idCity = $model->idCity;
                $ticket->email = $model->email;
                $ticket->title = Yii::t('app', 'Complaints_and_suggestions');
                $ticket->type = Ticket::STATUS_QUESTION;
                $ticket->body = '(Имя пользователя: ' . $model->name . ') ' . $model->text;

                if ($ticket->save()) {
                    return $this->renderAjax('modal_message', ['text' => Yii::t('app', 'Send_ok')]);
                } else {
                    return $this->renderAjax('modal_message', ['text' => Yii::t('app', 'Send_error')]);
                }
            }
        }

        return $this->renderAjax('modal_complaints', ['model' => $model]);
    }

    public function actionContacts()
    {
        $breadcrumbs = [['label' => Yii::t('app', 'Our_contacts')]];
        $this->breadcrumbs = $breadcrumbs;

        return $this->render('contacts', []);
    }

    public function actionReklama()
    {
        $breadcrumbs = [['label' => Yii::t('app', 'Advertising_on_the_website')]];
        $this->breadcrumbs = $breadcrumbs;

        return $this->render('reklama');
    }

    public function actionComplaints()
    {
        $breadcrumbs = [
            ['label' => Yii::t('app', 'Complaints_and_suggestions')],
        ];
        $this->breadcrumbs = $breadcrumbs;

        return $this->render('complaints', []);
    }

    public function actionActionCompany()
    {
        $breadcrumbs = [
            ['label' => Yii::t('app', 'Shares_of_our_company')],
        ];
        $this->breadcrumbs = $breadcrumbs;

        return $this->render('action_company', []);
    }

    public function actionSupport()
    {
        Yii::$app->view->theme->pathMap = ['@app/views' => '@frontend/themes/super_list'];

        $breadcrumbs = [['label' => Yii::t('app', 'Support')]];
        $this->breadcrumbs = $breadcrumbs;

        return $this->render('support');
    }

    public function actionOfficialInfo()
    {
        $breadcrumbs = [['label' => Yii::t('app', 'Official_information')]];
        $this->breadcrumbs = $breadcrumbs;

        return $this->render('official_info', []);
    }

    public function actionSiteRules()
    {
        $breadcrumbs = [['label' => Yii::t('app', 'Terms_of_Use')]];
        $this->breadcrumbs = $breadcrumbs;

        return $this->render('site_rules');
    }

    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        if ($exception !== null) {

            return $this->render('error', [
                'exception' => $exception,
                'statusCode' => \Yii::$app->errorHandler->code,
            ]);
        }
    }
/*
    public function actionRss()
    {
        if (!Yii::$app->request->city or !Yii::$app->params['SUBDOMAINID']) throw new HttpException(404);
        $query = Wall::find();
        $query->where(['idCity' => Yii::$app->params['SUBDOMAINID']]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy('id DESC'),
            'pagination' => ['pageSize' => false],
        ]);
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
        return $this->renderPartial('rss', [
            'dataProvider' => $dataProvider,
        ]);
    }
*/
    public function actionAboutCity()
    {
        if (empty(Yii::$app->request->city)) {
            throw new HttpException(404);
        }
        /** @var CityDetail $model */
        $model = CityDetail::find()->where(['id' => Yii::$app->params['SUBDOMAINID']])->one();
        
        $this->breadcrumbs = ['label' => Yii::t('app', 'About_city')];

        $title = !$model ? $this->seo_title_city : $model->seo_title;
        $desc = !$model ? $this->seo_description_city : $model->seo_description;
        $key = !$model ? $this->seo_keywords_city : $model->seo_keywords;
        SeoHelper::registerAllMeta($this->view, ['title' => $title, 'description' => $desc, 'keywords' => $key]);
        SeoHelper::registerOgImage();
        $title = Yii::t('app', 'About_city') . ' - ' . Yii::$app->name . ((Yii::$app->params['SUBDOMAINTITLE']) ? ' - ' . Yii::$app->params['SUBDOMAINTITLE'] : '');

        return $this->render('about', ['model' => $model, 'title' => $title]);
    }

    public function actionImage($alias, $url)
    {
        $alias = Html::encode(trim(strip_tags($alias)));
        $url = Html::encode(trim(strip_tags($url)));
        $url = "https://s3-eu-west-1.amazonaws.com/files1q/{$alias}/{$url}";

        $head = get_headers($url, true);
        $doesExist = ('HTTP/1.1 200 OK' === $head[0]) ? true : false;
        if ($doesExist and $img = fopen($url, 'r')) {
            $headers = [
                'Content-type: image/jpeg',
                "Date: {$head['Date']}",
                "Last-Modified: {$head['Last-Modified']}",
                "Accept-Ranges: {$head['Accept-Ranges']}",
                "Content-Length: {$head['Content-Length']}",
            ];
            foreach ($headers as $header) {
                header($header, true, 200);
            }
            while (!feof($img)) {
                echo fread($img, 1024 * 16);
            }
            fclose($img);
        } else {
            throw new HttpException(404);
        }
    }

    public function actionLiqpay()
    {
        $order = new Order('5', LiqPayCurrency::UAH, 'ololo', hash('md5', ('order' . 'cat' . time())));
        $subscribe = new Subscribe(time(), Subscribe::MONTH);
        
        return $this->render('liqpay', ['order' => $order, 'subscribe' => $subscribe]);
    }

    public function actionLiqpayCallback()
    {
        $data_post = Yii::$app->request->post('data');
        $signature = Yii::$app->request->post('signature');

        if (!$data_post or !$signature or !Yii::$app->liqPay->checkCallbackSignature($data_post, $signature)) {
            throw new HttpException(404);
        }

        $data = Json::decode(base64_decode($data_post));

        if (!empty($data['order_id'])) {
            $payment = LiqpayPayment::find()->where(['order_id' => $data['order_id']])->one();
        }

        if (empty($payment)) {
            $payment = new LiqpayPayment(['order_id' => $data['order_id']]);
        }

        if (!empty($data['action'])) {
            $payment->action = $data['action'];
        }

        if (!empty($data['amount'])) {
            $payment->amount = $data['amount'];
        }

        if (!empty($data['currency'])) {
            $payment->currency = $data['currency'];
        }

        if (!empty($data['status'])) {
            $payment->status = $data['status'];
        }

        $payment->data = $data_post;

        if (!$payment->save()) {
            throw new HttpException(500, var_export($payment->getErrors(), true));
        }

        if (empty($data['status']) || empty($data['order_id'])) {
            throw new HttpException(500, 'status or order id are empty');
        }

        //"{$now}_business_{$model->id}_own_{type}_from_{$user->id}"
        $order = explode('_', $data['order_id']);

        $business = empty($order[2]) ? null : $order[2];
        $type = empty($order[4]) ? null : $order[4];
        $user = empty($order[6]) ? null : $order[6];

        $business = $business ? Business::findOne($business) : null;
        $user = $user ? User::findOne($user) : null;

        if (!$business || !$user || !$type) {
            return false;
        }
        $tariffs = $business->getTariffs($user);
        $duration = (isset($tariffs[$type]) && isset($tariffs[$type]['duration'])) ? $tariffs[$type]['duration'] : 1;
        $from = $business->due_date ? strtotime($business->due_date) : time();
        $to = time() + 2592000 * $duration;

        if ($data['status'] !== LiqPayStatuses::SUCCESS) {
            Yii::$app->mailer->compose(['html' => 'error-invoice-html'], [
                'description' => empty($data['description']) ? 'Оплата управления предприятием' : $data['description'],
                'price' => empty($data['amount']) ? null : $data['amount'],
                'from' => date('H:i d.m.Y', $from),
                'to' => date('H:i d.m.Y', $to),
                'business_title' => $business->title,
                'order_id' => $data['order_id'],
            ])
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo($user->email)
                ->setSubject('CityLife. Ошибюка оплаты.')
                ->send();
            return false;
        }
        
        $invoice = Invoice::find()->where(['order_id' => $data['order_id']])->one();
        if (!$invoice) {
            $invoice = new Invoice();
            $invoice->user_id = $user->id;
            $invoice->object_type = File::TYPE_BUSINESS;
            $invoice->object_id = $business->id;
            $invoice->paid_from = $from;
            $invoice->order_id = $data['order_id'];
        }
        $invoice->paid_status = Invoice::PAID_YES;
        $invoice->paid_to = date('Y-m-d H:i:s', $to);

        if (!$invoice->save()) {
            throw new HttpException(500, var_export($invoice->getErrors(), true));
        }

        $business->due_date = date('Y-m-d H:i:s', $to);
        $business->save();
        Yii::$app->mailer->compose(['html' => 'invoice-html'], [
            'description' => empty($data['description']) ? 'Оплата управления предприятием' : $data['description'],
            'price' => empty($data['amount']) ? null : $data['amount'],
            'from' => date('H:i d.m.Y', $from),
            'to' => date('H:i d.m.Y', $to),
            'business_title' => $business->title,
            'business_link' => $link = 'https://' . $business->getSubDomain() . Yii::$app->params['appFrontend'] . $business->getFrontendUrl(),
            'invoice' => $invoice,
            'user' => $user,
        ])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($user->email)
            ->setSubject('CityLife. Оплата за услугу принята.')
            ->send();

        $notification = new Notification();
        $notification->status = Notification::STATUS_HIDE;
        $notification->type_js = Notification::TYPE_JS_PAYMENT_RECIEVE;
        $notification->sender_id = $invoice->user_id;
        $notification->save();

        return true;
    }

    public function actionAddFavorite($action, $id, $type)
    {
        $user = Yii::$app->user->identity;
        $type = (int)$type;
        $id = (string)$id;

        if (!$user or !Yii::$app->request->isAjax) {
            throw new HttpException(404);
        }

        $favorite = Favorite::find()->where(['user_id' => $user->id, 'object_type' => $type, 'object_id' => $id])->one();

        switch ($action) {
            case 'add':
                if (!$favorite) {
                    $favorite = new Favorite(['user_id' => $user->id, 'object_type' => $type, 'object_id' => $id]);
                    if (!$favorite->save()) {
                        throw new HttpException(500, implode(' ', $favorite->firstErrors));
                    }
                }
                break;
            case 'remove':
                if ($favorite) {
                    $favorite->delete();
                }
                break;
            default:
                throw new HttpException(404);
        }
    }
    
    public function actionOwnerSupport()
    {
        return $this->renderAjax('owner-support');
    }
}
