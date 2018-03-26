<?php

namespace backend\controllers;

use yii;
use common\models\Action;
use common\models\File;
use common\models\CountViews;
use backend\models\search\Action as ActionSearch;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use common\models\Log;
use common\models\Wall;

/**
 * ActionController implements the CRUD actions for Action model.
 */
class ActionController extends BaseAdminController
{
    /**
     * Lists all Action models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ActionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Action model.
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
     * Creates a new Action model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param null $idCompany
     * @return mixed
     */
    public function actionCreate($idCompany = null)
    {
        $model = new Action();
        if ($idCompany) {
            $model->idCompany = $idCompany;
        }
        
        if ($model->load(Yii::$app->request->post()) and $model->save()) {
            $this->saveTegs($model->tags);
            Log::addAdminLog("action[create]  ID: {$model->id}", $model->id, Log::TYPE_ACTION);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Action model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $actions
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $actions = '')
    {
        $model = $this->findModel($id);
        $model->tags = explode(', ', $model->tags);
        
        if ($actions === 'deleteImg') {
            Yii::$app->files->deleteFile($model, 'image');
            $model->image = '';
            if ($model->save()) {
                Log::addAdminLog("action[update]  ID: {$model->id}", $model->id, Log::TYPE_ACTION);
                
                return $this->redirect(Url::current(['actions' => null]));
            }
        } elseif ($model->load(Yii::$app->request->post()) and $model->save()) {
            $this->saveTegs($model->tags);
            Log::addAdminLog("action[update]  ID: {$model->id}", $model->id, Log::TYPE_ACTION);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if (isset($model->companyName->title)) {
            $model->companyTitle = $model->companyName->title;
        }

        if (isset($model->companyName->city->title)) {
            $model->actionCity = $model->companyName->city->title;
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing Action model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        CountViews::deleteAll(['pid' => $id, 'type' => File::TYPE_ACTION]);
        Wall::deleteAll(['pid' => $id, 'type' => File::TYPE_ACTION]);
        Log::addAdminLog("action[delete]  ID: {$id}", $id, Log::TYPE_ACTION);
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Action model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Action the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Action::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
