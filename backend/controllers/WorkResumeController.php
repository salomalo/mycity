<?php

namespace backend\controllers;

use common\models\File;
use yii;
use common\models\WorkResume;
use backend\models\search\WorkResume as WorkResumeSearch;
use yii\web\NotFoundHttpException;
use common\models\Log;

/**
 * WorkResumeController implements the CRUD actions for WorkResume model.
 */
class WorkResumeController extends BaseAdminController
{
    /**
     * Lists all WorkResume models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorkResumeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WorkResume model.
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
     * Creates a new WorkResume model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param null $id
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $model = new WorkResume();
        
        if($id != null){
            $model->idCategory = $id;
        }

        if ($model->load(Yii::$app->request->post())) {
            
            \Yii::$app->files->upload($model, 'photoUrl');
            
            if ($model->save()) {
                Log::addAdminLog("work resume[create]  ID: {$model->id}", $model->id, Log::TYPE_RESUME);
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WorkResume model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $actions
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $actions = '')
    {
        $model = $this->findModel($id);
        
        if($actions == 'deleteImg'){
            \Yii::$app->files->deleteFile($model, 'photoUrl');
            $model->photoUrl = '';
            $model->save();
            Log::addAdminLog("work resume[update]  ID: {$model->id}", $model->id, Log::TYPE_RESUME);
        }

        if ($model->load(Yii::$app->request->post())) {
            
            \Yii::$app->files->upload($model, 'photoUrl');
            
            if ($model->save()) {
                Log::addAdminLog("work resume[update]  ID: {$model->id}", $model->id, Log::TYPE_RESUME);
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WorkResume model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        \Yii::$app->files->deleteFile($this->findModel($id), 'photoUrl');
        $model = $this->findModel($id);
        $model->delete();

        Log::addAdminLog("work resume[delete]  ID: {$id}", $id, Log::TYPE_RESUME);

        return $this->redirect(['index']);
    }

    /**
     * Finds the WorkResume model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorkResume the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorkResume::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
