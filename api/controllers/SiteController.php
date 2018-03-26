<?php
namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use frontend\models\PasswordResetRequestForm;
use frontend\models\SignupForm;
use backend\extensions\S3UploadedFile;
use yii\filters\VerbFilter;
use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\Json;
use common\models\City;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $layout = 'site';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [];
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
//        return $this->render('index');
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
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }
    
    public function actionCity() {
        
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            
            $list = City::find()->where(['idRegion'=>$id])->asArray()->all();
            $selected  = '';
            if($list){
                foreach ($list as $i => $item){
                    $out[] = ['id' => $item['id'], 'name' => $item['title']];
                    if ($i == 0) {
                        $selected = $item['id'];
                    }
                }
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        
        if (isset($_POST['City'])) {
            $city = City::find()->select('subdomain')->where(['id'=>(int)$_POST['City']['id']])->one();
            if($city){
                $arr = explode('//', \Yii::$app->request->baseUrl);
                $url = $arr[0] . '//' . $city->subdomain . '.' . $arr[1];
                \Yii::$app->response->redirect($url);
            }
        }
        
        //echo Json::encode(['output' => '', 'selected'=>'']);
    }
}
