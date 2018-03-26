<?php

namespace office\controllers;

use common\models\Log;
use common\models\SigninForm;
use common\models\User;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use dektrium\user\controllers\SecurityController as BaseSecurityController;
use yii\helpers\Url;
use yii\web\ErrorHandler;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\HttpException;
use dektrium\user\models\LoginForm;

class SecurityController extends BaseSecurityController
{
    public $layout = 'login';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        $behaviors['access']['rules'][1]['actions'][] = 'city';

        $behaviors ['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
            ],
        ];
        return $behaviors;
    }

    public function actionLogin()
    {
        /** @var SigninForm $model */
        $model = Yii::createObject(SigninForm::className());

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->getRequest()->post())) {
            $model->rememberMe = true;
            if ($model->login()) {
                $userId = Yii::$app->user->id;
                Log::addUserLog(Log::$types[Log::TYPE_LOGIN], $userId, Log::TYPE_LOGIN);

                /** @var User $user */
                $user = User::find()->where(['or', ['username' => $model->login], ['email' => $model->login]])->one();
                if (is_object($user)) {
                    Yii::$app->session->setFlash('officeLogin', 'You logged');

                    $user->updateAttributes(['last_activity']);
                }
                return $this->goBack();
            }
        }

        return $this->render('login', [
            'model'  => $model,
            'module' => $this->module,
        ]);
    }

    public function actionCity()
    {
        $this->layout = 'login';
        $user = Yii::$app->user->identity;

        if (($post = Yii::$app->request->post('User')) and isset($post['city_id'])) {
            $user->city_id = $post['city_id'];
            if ($user->save()) {
                $back = Url::previous('back_from_city');
                return $back ? $this->redirect($back) : $this->goBack();
            }
        }

        return $this->render('city', ['user' => $user]);
    }
}
