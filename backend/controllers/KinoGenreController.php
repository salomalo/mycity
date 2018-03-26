<?php

namespace backend\controllers;

use Yii;
use common\models\KinoGenre;
use common\models\search\KinoGenre as KinoGenreSearch;
use backend\controllers\BaseAdminController;
use yii\web\NotFoundHttpException;
use common\models\Log;

/**
 * KinoGenreController implements the CRUD actions for KinoGenre model.
 */
class KinoGenreController extends BaseAdminController
{
    /**
     * Lists all KinoGenre models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KinoGenreSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KinoGenre model.
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
     * Creates a new KinoGenre model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KinoGenre();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addAdminLog("kino genre[create]  ID: {$model->id}", $model->id, Log::TYPE_KINO_GENRE);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing KinoGenre model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addAdminLog("kino genre[update]  ID: {$model->id}", $model->id, Log::TYPE_KINO_GENRE);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing KinoGenre model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Log::addAdminLog("kino genre[delete]  ID: {$id}", $id, Log::TYPE_KINO_GENRE);

        return $this->redirect(['index']);
    }

    /**
     * Finds the KinoGenre model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KinoGenre the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KinoGenre::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
