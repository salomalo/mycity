<?php

namespace backend\controllers;

use Yii;
use common\models\ActionCategory;
use common\models\search\ActionCategory  as ActionCategorySearch;
use yii\web\NotFoundHttpException;
use backend\controllers\BaseAdminController;
use common\models\Log;

/**
 * ActionCategoryController implements the CRUD actions for ActionCategory model.
 */
class ActionCategoryController extends BaseAdminController
{
    /**
     * Lists all ActionCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ActionCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ActionCategory model.
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
     * Creates a new ActionCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ActionCategory();

        if ($model->load(Yii::$app->request->post())) {
            
            \Yii::$app->files->upload($model, 'image');
            
            if($model->save()){
                Log::addAdminLog("action category[create] ID: {$model->id}", $model->id, Log::TYPE_ACTION_CATEGORY);
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ActionCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $actions = '')
    {
        $model = $this->findModel($id);
        
        if($actions == 'deleteImg'){
                \Yii::$app->files->deleteFile($model, 'image');
                $model->image = '';
                $model->save();
        }

        if ($model->load(Yii::$app->request->post())) {
            
            \Yii::$app->files->upload($model, 'image');
            
            if($model->save()){
                Log::addAdminLog("action category[update] ID: {$model->id}", $model->id, Log::TYPE_ACTION_CATEGORY);
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ActionCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        \Yii::$app->files->deleteFile($this->findModel($id), 'image');
        $this->findModel($id)->delete();
        Log::addAdminLog("action category[delete] ID: {$id}", $id, Log::TYPE_ACTION_CATEGORY);

        return $this->redirect(['index']);
    }

    /**
     * Finds the ActionCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActionCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ActionCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
