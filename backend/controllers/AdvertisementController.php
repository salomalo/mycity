<?php

namespace backend\controllers;

use yii;
use common\models\Advertisement;
use backend\models\search\Advertisement as AdvertisementSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdvertisementController implements the CRUD actions for Advertisement model.
 */
class AdvertisementController extends BaseAdminController
{
    /**
     * Lists all Advertisement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdvertisementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Advertisement model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    /**
     * Creates a new Advertisement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Advertisement();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Advertisement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) and $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing Advertisement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

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

    /**
     * Finds the Advertisement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Advertisement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Advertisement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDelImg($id)
    {
        $id = (int)$id;
        if ($model = $this->findModel($id)) {
            Yii::$app->files->deleteFile($model, 'image');
            $model->image = '';
            $model->update(false, ['image']);
        }
        return $this->redirect(['update', 'id' => $id]);
    }
}
