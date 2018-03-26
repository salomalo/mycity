<?php

namespace backend\controllers;

use Yii;
use common\models\LogParseBusiness;
use common\models\search\LogParseBusiness as LogParseBusinessSearch;
use yii\web\NotFoundHttpException;
use backend\controllers\BaseAdminController;

/**
 * LogParseBusinessController implements the CRUD actions for LogParseBusiness model.
 */
class LogParseBusinessController extends BaseAdminController
{
    /**
     * Lists all LogParseBusiness models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LogParseBusinessSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing LogParseBusiness model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the LogParseBusiness model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LogParseBusiness the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LogParseBusiness::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
