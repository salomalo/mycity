<?php

namespace backend\controllers;

use yii;
use common\models\WorkVacantion;
use common\models\File;
use common\models\CountViews;
use backend\models\search\WorkVacantion as WorkVacantionSearch;
use yii\web\NotFoundHttpException;
use common\models\Log;

/**
 * WorkVacantionController implements the CRUD actions for WorkVacantion model.
 */
class WorkVacantionController extends BaseAdminController
{
    /**
     * Lists all WorkVacantion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorkVacantionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WorkVacantion model.
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
     * Creates a new WorkVacantion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param null $id
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $model = new WorkVacantion();
        
        if($id != null){
            $model->idCategory = $id;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addAdminLog("work vacantion[create]  ID: {$model->id}", $model->id, Log::TYPE_WORK_VACANTION);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WorkVacantion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addAdminLog("work vacantion[update]  ID: {$model->id}", $model->id, Log::TYPE_WORK_VACANTION);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WorkVacantion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        CountViews::deleteAll(['pid'=>$id, 'type'=>File::TYPE_WORK_VACANTION]);
        $model = $this->findModel($id);
        $model->delete();
        Log::addAdminLog("work vacantion[delete]  ID: {$id}", $id, Log::TYPE_WORK_VACANTION);
        return $this->redirect(['index']);
    }

    /**
     * Finds the WorkVacantion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorkVacantion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorkVacantion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
