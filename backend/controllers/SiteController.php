<?php
namespace backend\controllers;

use common\extensions\ChartsWidget;
use common\models\Log;
use yii;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use yii\web\Cookie;
use common\models\City;

/**
 * Site controller
 */
class SiteController extends BaseAdminController
{
    public $layout = 'login';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login','error'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','index','error', 'city'],
                        'allow' => true,
                        'roles' => ['@'],
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
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'main';
        $form_input = Yii::$app->request->post('period', null);

        if (is_null($form_input)) {
            $form_input = Yii::$app->request->cookies->getValue('highcharts_period', 'M');
        } else {
            Yii::$app->response->cookies->add(new Cookie(['name' => 'highcharts_period', 'value' => $form_input]));
        }

        $duration = 6;
        $periodFunc = Log::getIntervalFunc($form_input);
        $betweenFunc = Log::getBetweenFunc($form_input);
        $charts[] = Log::getLoginAndRegChart($duration, $periodFunc, $betweenFunc);
        $charts[] = Log::getAddContentChart($duration, $periodFunc, $betweenFunc);
        $charts[] = Log::getNewCommentChart($duration, $periodFunc, $betweenFunc);
        $charts[] = Log::getTotalActivityChart($duration, $periodFunc, $betweenFunc);

        return $this->render('index', ['charts' => $charts]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $adminId = Yii::$app->user->getIdentity()->id;
            Log::addAdminLog(Log::$types[Log::TYPE_LOGIN], $adminId, Log::TYPE_LOGIN);
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

    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            /** @noinspection PhpUndefinedFieldInspection */
            return $this->render('error', [
                'exception' => $exception,
                'statusCode'=>\Yii::$app->errorHandler->code,
            ]);
        }
        return false;
    }  
    
    public function actionCity() {
        
        if (isset($_POST['do'])) {
            Yii::$app->response->cookies->remove('SUBDOMAINID');
            Yii::$app->response->cookies->remove('SUBDOMAIN');
        }
        
        if (isset($_POST['City'])) {
            
            $getCookies = Yii::$app->getRequest()->getCookies();
            
            if($getCookies->has('SUBDOMAINID')){
                Yii::$app->response->cookies->remove('SUBDOMAINID');
                Yii::$app->response->cookies->remove('SUBDOMAIN');
            }
           
            $this->addCookies('SUBDOMAINID', $_POST['City']['id']);
            
            $city = City::findOne(['id' => (int)$_POST['City']['id']]);
            $this->addCookies('SUBDOMAIN', $city->subdomain);
        }
        
//        $this->redirect(Url::previous('active_url'));
        if ($ref = Yii::$app->request->referrer) {
            return $this->redirect($ref);
        } else {
            return $this->goHome();
        }
    }
    
    public function addCookies($name, $value){
        $cookies = Yii::$app->getResponse()->getCookies();
        
        $cookie = new Cookie([
            'name' => $name,
            'value' => $value,
            'expire' => time() + 86400 * 365,
        ]);

        $cookies->add($cookie);
    }
}
