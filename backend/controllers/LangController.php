<?php

namespace backend\controllers;

use Yii;
use common\models\Lang;
use common\models\search\Lang as LangSearch;
use backend\controllers\BaseAdminController;
use yii\web\NotFoundHttpException;
use common\models\Log;

/**
 * LangController implements the CRUD actions for Lang model.
 */
class LangController extends BaseAdminController
{
    /**
     * Lists all Lang models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LangSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lang model.
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
     * Creates a new Lang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Lang();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addAdminLog("lang[create]  ID: {$model->id}", $model->id, Log::TYPE_LANG);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Lang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addAdminLog("lang[update]  ID: {$model->id}", $model->id, Log::TYPE_LANG);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Lang model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Log::addAdminLog("lang[delete]  ID: {$id}", $id, Log::TYPE_LANG);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Lang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lang::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
