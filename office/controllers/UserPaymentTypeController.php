<?php

namespace office\controllers;

use common\models\UserPaymentType;
use yii;
use yii\web;
use yii\web\HttpException;
use yii\filters\VerbFilter;

class UserPaymentTypeController extends DefaultController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
//        $behaviors ['verbs'] = [
//            'class' => VerbFilter::className(),
//            'actions' => ['delete' => ['post']],
//        ];
        return $behaviors;
    }

    public function actionCreate()
    {
        $model = new UserPaymentType();

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->id;
            if ($model->save()) {
                return $this->redirect(['/user/settings/payment-types']);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = UserPaymentType::findOne($id);
        if (!$model) {
            throw new HttpException(404);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->id;
            if ($model->save()) {
                return $this->redirect(['/user/settings/payment-types']);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = UserPaymentType::findOne($id);
        if (!$model) {
            throw new HttpException(404);
        }
        $model->delete();

        return $this->redirect(['/user/settings/payment-types']);
    }
}
