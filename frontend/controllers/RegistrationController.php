<?php

namespace frontend\controllers;

use common\models\Log;
use common\models\User;
use common\models\UserRegInfo;
use dektrium\user\controllers\RegistrationController as BaseRegistrationController;
//use dektrium\user\models\RegistrationForm as Form;
use common\models\RegistrationForm as Form;
use dosamigos\transliterator\TransliteratorHelper;
use yii;
use yii\filters\AccessControl;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class RegistrationController extends BaseRegistrationController
{
    public $breadcrumbs = [];
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['register-ajax', 'register-landing'],
                'rules' => [
                    [
                        'actions' => ['register-ajax', 'register-landing'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['register-ajax', 'register-landing'],
                        'allow' => true,
                        'verbs' => ['POST'],
                    ],
                    ['allow' => true, 'actions' => ['register', 'connect'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['confirm', 'resend'], 'roles' => ['?', '@']],
                ],
            ],
        ];
    }

    public function actionRegisterAjax()
    {
        if ((!Yii::$app->request->isAjax) or (!$this->module->enableRegistration)) {
            throw new NotFoundHttpException();
        }
        /** @var Form $model */
        $model = Yii::createObject(Form::className());

        if ((isset($_POST['register-form'])) and ($form = $_POST['register-form'])) {
            $fields = ['username', 'email', 'password', 'password2', 'apply', 'phone'];
            foreach ($fields as $field) {
                $model->{$field} = $form[$field] ? $form[$field] : null;
            }
            if ($user = $model->register()) {
                Yii::$app->session->removeFlash('info');
                /** @var User $user */
                $user = User::find()->where(['email' => $model->email])->one();
                Log::addUserLog(Log::$types[Log::TYPE_REGISTER], $user->id, Log::TYPE_REGISTER);

                if (is_object($user)) {
                    $user->updateAttributes(['last_activity' => date('Y-m-d H:i:s')]);
                }
                Yii::$app->session->setFlash('frontendRegister', 'Register success');
                return $this->renderAjax('activate', ['email' => $model->email]);
            }
        }

        return $this->renderAjax('register', [
            'model' => $model,
            'module' => $this->module,
        ]);
    }

    public function actionRegisterLanding($utm_source = null, $utm_campaign = null)
    {
        /** @var Form $model */
        $model = Yii::createObject(Form::className());

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ((isset($_POST['register-form'])) and ($form = $_POST['register-form'])) {
            $fields = ['username', 'email', 'password', 'apply', 'phone'];
            foreach ($fields as $field) {
                $model->{$field} = !empty($form[$field]) ? $form[$field] : null;
            }
            //нужно для того, что бы форма прошла валидацию
            $model->password2 = $model->password;
            if ($user = $model->registerLanding()) {
                Yii::$app->session->removeFlash('info');
                /** @var User $user */
                $user = User::find()->where(['email' => $model->email])->one();

                if (is_object($user)) {
                    $user->updateAttributes(['last_activity' => date('Y-m-d H:i:s')]);
                    Log::addUserLog(Log::$types[Log::TYPE_REGISTER], $user->id, Log::TYPE_REGISTER);

                    $userInfo = new UserRegInfo();
                    $userInfo->user_id = $user->id;
                    $userInfo->utm_source = $utm_source;
                    $userInfo->utm_campaing = $utm_campaign;

                    $userInfo->save();
                }

                Yii::$app->session->setFlash('landingFlash', $this->renderPartial('activate_landing', ['email' => $model->email]));

                return $this->redirect(['/landing/index']);
            }
        }

        return $this->redirect(['/landing/index']);
    }
    
    public function actionConnect($code)
    {
        $account = $this->finder->findAccount()->byCode($code)->one();

        if ($account === null || $account->getIsConnected()) {
            throw new NotFoundHttpException();
        }

        $data = Json::decode($account->data);

        $username = isset($data['first_name']) ? $data['first_name'] : '';
        $username .= isset($data['last_name']) ? " {$data['last_name']}" : '';
        $username = Inflector::slug(TransliteratorHelper::process($username), '_', true);

        /** @var User $user */
        $user = Yii::createObject([
            'class'    => User::className(),
            'scenario' => 'connect',
            'username' => $username ? $username : $account->username,
            'email'    => $account->email,
        ]);

        $oldUser = User::findByEmail($user->email);
        if ($oldUser){
            return $this->render('user_is_register');
        }

        $event = $this->getConnectEvent($account, $user);

        $this->trigger(self::EVENT_BEFORE_CONNECT, $event);

        if ($user->load(Yii::$app->request->post()) && $user->create()) {
            $account->connect($user);
            $this->trigger(self::EVENT_AFTER_CONNECT, $event);
            Yii::$app->user->login($user, $this->module->rememberFor);
            return $this->redirect(Yii::$app->session->get('urlBeforeConnect'));
        }

        $this->breadcrumbs[] = Yii::t('app', "Registration through {$event->account->provider}");

        return $this->render('connect', [
            'model'   => $user,
            'account' => $account,
        ]);
    }

    public function actionConfirm($id, $code)
    {
        $user = $this->finder->findUserById($id);

        if ($user === null || $this->module->enableConfirmation == false) {
            throw new NotFoundHttpException();
        }

        $event = $this->getUserEvent($user);

        $this->trigger(self::EVENT_BEFORE_CONFIRM, $event);

        $user->attemptConfirmation($code);

        $this->trigger(self::EVENT_AFTER_CONFIRM, $event);

        Yii::$app->session->setFlash('successConfirmRegister', 'You confirmed');
        $this->redirect(['/']);
//        return $this->render('/message', [
//            'title'  => \Yii::t('user', 'Account confirmation'),
//            'module' => $this->module,
//        ]);
    }
}