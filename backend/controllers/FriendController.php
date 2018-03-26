<?php

namespace backend\controllers;

use Yii;
use common\models\Friend;
use common\models\search\Friend as FriendSearch;
use yii\web\NotFoundHttpException;
use backend\controllers\BaseAdminController;
use common\models\Log;

/**
 * FriendController implements the CRUD actions for Friend model.
 */
class FriendController extends BaseAdminController
{
    /**
     * Lists all Friend models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FriendSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Friend model.
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
     * Creates a new Friend model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Friend();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addAdminLog("friend[create]  ID: {$model->id}", $model->id, Log::TYPE_FRIEND);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Friend model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addAdminLog("friend[update]  ID: {$model->id}", $model->id, Log::TYPE_FRIEND);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Friend model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Log::addAdminLog("friend[delete]  ID: {$id}", $id, Log::TYPE_FRIEND);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Friend model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Friend the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Friend::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
