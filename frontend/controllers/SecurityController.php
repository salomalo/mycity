<?php
namespace frontend\controllers;

use common\models\Log;
use common\models\SigninForm;
use common\models\User;
use dektrium\user\models\Account;
use yii;
use dektrium\user\controllers\SecurityController as BaseSecurityController;
use dektrium\user\models\LoginForm;
use yii\authclient\ClientInterface;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class SecurityController extends BaseSecurityController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['rules'][0]['actions'][] = 'login-ajax';
        $behaviors['access']['rules'][1]['actions'][] = 'login-ajax';
        return $behaviors;
    }

    public function actionLoginAjax($redirectUrl = null)
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        if (!Yii::$app->user->isGuest) {
            $this->goBack();
        }
        /** @var LoginForm $model */
        $model = Yii::createObject(SigninForm::className());

        if ($model->load(Yii::$app->request->post()) and $model->login()) {
            $user = User::findOne(Yii::$app->user->id);

            Log::addUserLog(Log::$types[Log::TYPE_LOGIN], $user->id, Log::TYPE_LOGIN);

            if (is_object($user)) {
                $user->updateAttributes(['last_activity' => date('Y-m-d H:i:s')]);
            }
            Yii::$app->session->setFlash('frontendLogin', 'You logged');

            if ($redirectUrl) {
                return $this->redirect($redirectUrl);
            } else {
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        return $this->renderAjax('login_ajax', [
            'model' => $model,
            'module' => $this->module,
            'redirectUrl' => $redirectUrl
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }

        /** @var LoginForm $model */
        $model = Yii::createObject(SigninForm::className());
        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);
        $this->trigger(self::EVENT_BEFORE_LOGIN, $event);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            $this->trigger(self::EVENT_AFTER_LOGIN, $event);
            /** @var User $user */
            $user = User::findOne(Yii::$app->user->id);

            Log::addUserLog(Log::$types[Log::TYPE_LOGIN], $user->id, Log::TYPE_LOGIN);

            if (is_object($user)) {
                $user->updateAttributes(['last_activity' => date('Y-m-d H:i:s')]);
            }
            Yii::$app->session->setFlash('frontendLogin', 'You logged');

            return $this->goBack();
        }

        return $this->render('login', [
            'model'  => $model,
            'module' => $this->module,
            'redirectUrl' => Yii::$app->request->referrer
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->getUser()->logout();
        if ($ref = Yii::$app->request->referrer) {
            return $this->redirect($ref);
        } else {
            return $this->goHome();
        }
    }

    public function authenticate(ClientInterface $client)
    {
        $account = $this->finder->findAccount()->byClient($client)->one();

        if (!$this->module->enableRegistration && ($account === null || $account->user === null)) {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'Registration on this website is disabled'));
            $this->action->successUrl = Url::to(['/user/security/login']);
            return;
        }

        if ($account === null) {
            /** @var Account $account */
            $accountObj = Yii::createObject(Account::className());
            $account = $accountObj::create($client);
        }

        $event = $this->getAuthEvent($account, $client);

        $this->trigger(self::EVENT_BEFORE_AUTHENTICATE, $event);

        if ($account->user instanceof User) {
            $account->user->updateAttributes(['last_activity' => date('Y-m-d H:i:s')]);
            if ($account->user->isBlocked) {
                Yii::$app->session->setFlash('danger', Yii::t('user', 'Your account has been blocked.'));
                $this->action->successUrl = Url::to(['/user/security/login']);
            } else {
                Yii::$app->user->login($account->user, $this->module->rememberFor);
                $this->action->successUrl = Yii::$app->session->get('urlBeforeConnect');
            }
        } else {
            $this->action->successUrl = $account->getConnectUrl();
        }

        $this->trigger(self::EVENT_AFTER_AUTHENTICATE, $event);
    }
}
