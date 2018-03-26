<?php

namespace office\controllers;

use common\models\Profile;
use common\models\Provider;
use common\models\UserPaymentType;
use yii;
//use dektrium\user\models\Profile;
use dektrium\user\controllers\SettingsController as SettingsControllerBasic;

/**
 * SettingsController manages updating user settings (e.g. profile, email and password).
 *
 * @property \dektrium\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SettingsController extends SettingsControllerBasic
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['payment-types', 'providers'],
            'roles' => ['@'],
        ];

        return $behaviors;
    }

    /**
     * Shows profile settings form.
     *
     * @return string|\yii\web\Response
     */
    public function actionProfile()
    {

        $model = Profile::findOne(Yii::$app->user->identity->id);
        if ($model == null) {
            $model = new Profile();
            $model->user_id = Yii::$app->user->identity->id;

            $model->save();
        }

        $event = $this->getProfileEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Your profile has been updated'));
            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
            return $this->refresh();
        }

        return $this->render('profile', [
            'model' => $model,
        ]);

//        $model = $this->finder->findProfileById(Yii::$app->user->identity->getId());
//
//        if ($model == null) {
//            $model = Yii::createObject(Profile::className());
//            $model->link('user', Yii::$app->user->identity);
//        }
//
//        $event = $this->getProfileEvent($model);
//
//        $this->performAjaxValidation($model);
//
//        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);
//        if ($model->load(Yii::$app->request->post()) and $model->save()
//            and $model->user->load(Yii::$app->request->post()) and $model->user->save()
//        ) {
//            Yii::$app->session->setFlash('success', Yii::t('user', 'Your profile has been updated'));
//            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
//
//            return $this->refresh();
//        }
//
//        return $this->render('profile', ['model' => $model]);
    }

    public function actionPaymentTypes()
    {
        $payments = UserPaymentType::find()->where(['user_id' => Yii::$app->user->id])->all();

        return $this->render('payment_types', ['payments' => $payments]);
    }

    public function actionProviders(){
        $providers = Provider::find()->where(['user_id' => Yii::$app->user->id])->all();

        return $this->render('providers', ['providers' => $providers]);
    }
}
