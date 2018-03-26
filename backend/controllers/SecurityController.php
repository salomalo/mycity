<?php

namespace backend\controllers;


use common\models\Log;
use common\models\User;
use dektrium\user\controllers\SecurityController as BaseSecurityController;
use Yii;
use backend\models\LoginForm;

class SecurityController extends BaseSecurityController
{
    public $layout = 'login';

    public function behaviors()
    {
        return parent::behaviors();
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }

        /** @var LoginForm $model */
        $model = Yii::createObject(LoginForm::className());

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->getRequest()->post())) {
            if ($this->getUserRole($model->login) === User::ROLE_ADMIN) {
                $model->login();
                $adminId = Yii::$app->user->getIdentity()->id;
                Log::addAdminLog(Log::$types[Log::TYPE_LOGIN], $adminId, Log::TYPE_LOGIN);
            } else {
                return $this->render('login', [
                    'model' => $model,
                    'module' => $this->module,
                    'error' => true
                ]);
            }
            return $this->goBack();
        }

        return $this->render('login', [
            'model'  => $model,
            'module' => $this->module,
            'error' => false
        ]);
    }

    private function getUserRole($login){
        $user = User::find()->where(['username' => $login])->one();
        return $user->role;
    }

}