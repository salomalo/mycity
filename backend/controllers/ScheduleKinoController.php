<?php

namespace backend\controllers;

use yii;
use common\models\ScheduleKino;
use backend\models\search\ScheduleKino as ScheduleKinoSearch;
use yii\web\NotFoundHttpException;
use common\models\Log;
use common\models\File;
use common\models\Wall;

/**
 * ScheduleKinoController implements the CRUD actions for ScheduleKino model.
 */
class ScheduleKinoController extends BaseAdminController
{
    /**
     * Lists all ScheduleKino models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ScheduleKinoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $getCookies = Yii::$app->request->cookies;
        
        if ($getCookies->has('SUBDOMAINID')) {
            $dataProvider->query->andWhere(['idCity' => $getCookies->get('SUBDOMAINID')]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ScheduleKino model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ScheduleKino model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ScheduleKino();
        $model->scenario = 'create';
        $model_backup = clone $model;
        
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                Log::addAdminLog("schedule kino[create]  ID: {$model->id}", $model->id, Log::TYPE_SCHEDULE_KINO);
                return $this->redirect(['view', 'id' => $model->id]);
            }
            $errors = $model->errors;
            $model = $model_backup;
            $model->addErrors($errors);
        }
        
        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing ScheduleKino model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Log::addAdminLog("schedule kino[update]  ID: {$model->id}", $model->id, Log::TYPE_SCHEDULE_KINO);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $errors = $model->errors;
                $model = $this->findModel($id);
                $model->addErrors($errors);
            }
        }
        $model->companyTitle = isset($model->company->title) ? $model->company->title : "";
        $model->scheduleCity = isset($model->company->city->title) ? $model->company->city->title : "";

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing ScheduleKino model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        $sc = ScheduleKino::find()->where(['idAfisha' => $model->idAfisha, 'idCity' => $model->idCity])->count();
        
        if($sc == 1){
            Wall::deleteAll(['pid'=>$model->afisha->id, 'type'=>File::TYPE_AFISHA, 'idCity' => $model->idCity]);
        }
        
        $this->findModel($id)->delete();
        Log::addAdminLog("schedule kino[delete]  ID: {$id}", $id, Log::TYPE_SCHEDULE_KINO);

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScheduleKino model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScheduleKino the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScheduleKino::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
