<?php
namespace office\controllers;

use common\models\Log;
use common\models\User;
use dektrium\user\controllers\RegistrationController as RegistrationControllerBasic;
//use dektrium\user\models\RegistrationForm;
use office\models\RegistrationForm;
use yii;
use yii\web\NotFoundHttpException;

/**
 * RegistrationController is responsible for all registration process, which includes registration of a new account,
 * resending confirmation tokens, email confirmation and registration via social networks.
 *
 * @property \dektrium\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RegistrationController extends RegistrationControllerBasic
{
    public $layout = 'login';

    public function actionRegister($tariff = null)
    {
        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException();
        }

        /** @var RegistrationForm $model */
        $model = Yii::createObject(RegistrationForm::className());
        $event = $this->getFormEvent($model);

        $this->trigger(self::EVENT_BEFORE_REGISTER, $event);

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->register($tariff)) {
            $this->trigger(self::EVENT_AFTER_REGISTER, $event);
            $user = User::find()->where(['email' => $model->email])->one();
            $user->updateAttributes(['last_activity' => date('Y-m-d H:i:s')]);

            Log::addUserLog(Log::$types[Log::TYPE_REGISTER], $user->id, Log::TYPE_REGISTER);

            $model = Yii::createObject(RegistrationForm::className());
            Yii::$app->session->setFlash('successOfficeRegister', 'You register');
            return $this->render('register', [
                'model'  => $model,
                'message'  => Yii::t('user', 'Your account has been created and a message with further instructions has been sent to your email'),
            ]);
        }

        return $this->render('register', [
            'model'  => $model,
            'module' => $this->module,
        ]);
    }

    /**
     * Confirms user's account. If confirmation was successful logs the user and shows success message. Otherwise
     * shows error message.
     *
     * @param int    $id
     * @param string $code
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionConfirm($id, $code, $redirect_url = null)
    {
        $user = $this->finder->findUserById($id);

        if ($user === null || $this->module->enableConfirmation == false) {
            throw new NotFoundHttpException();
        }

        $event = $this->getUserEvent($user);

        $this->trigger(self::EVENT_BEFORE_CONFIRM, $event);

        $user->attemptConfirmation($code);

        $this->trigger(self::EVENT_AFTER_CONFIRM, $event);

        if ($redirect_url){
            $this->redirect($redirect_url);
        } else {
            $this->redirect(['/site']);
        }
    }
}
