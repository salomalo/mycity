<?php

namespace office\controllers;

use common\models\Advertisement;
use InvalidArgumentException;
use yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;

class AdvertisementController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => ['delete' => ['post']],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [['allow' => true, 'roles' => ['@']]],
            ],
        ];
    }

    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $models = Advertisement::find()->where(['user_id' => $user->id])->all();

        return $this->render('index', ['models' => $models]);
    }

    public function actionSelect()
    {
        return $this->render('select');
    }

    public function actionCreate($pos)
    {
        if (!in_array($pos, array_keys(Advertisement::$positions))) {
            throw new InvalidArgumentException("Incorrect position $pos");
        }
        $user = Yii::$app->user->identity;
        $model = new Advertisement(['position' => (int)$pos, 'user_id' => $user->id, 'status' => Advertisement::STATUS_NOT_ACTIVE]);

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = $user->id;
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $user = Yii::$app->user->identity;
        /** @var Advertisement $model */
        $model = Advertisement::find()->where(['id' => $id, 'user_id' => $user->id])->one();

        if (empty($model)) {
            throw new HttpException(404);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = $user->id;
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $user = Yii::$app->user->identity;
        $model = Advertisement::find()->where(['id' => $id, 'user_id' => $user->id])->one();

        if (empty($model)) {
            throw new HttpException(404);
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionDisabledDates($pos, $city, $id = null)
    {
        if (!Yii::$app->request->isAjax) {
            throw new HttpException(404);
        }
        $pos = (int)$pos;
        $id = (int)$id;
        $city = (int)$city;

        $dates = [];
        if ($id and $pos and $city) {
            $model = Advertisement::findOne($id);
            $dates = $model->getDisabledDatesWithoutCurrent($pos, $city);
        } elseif ($pos and $city) {
            $dates = Advertisement::getDisabledDates($pos, $city);
        }

        return json_encode($dates);
    }

    public function actionDelImg($id)
    {
        $id = (int)$id;
        if ($model = Advertisement::findOne($id)) {
            Yii::$app->files->deleteFile($model, 'image');
            $model->image = '';
            $model->update(false, ['image']);
        }
        return $this->redirect(['update', 'id' => $id]);
    }
}
