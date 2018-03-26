<?php

namespace backend\controllers;

use Yii;
use common\models\OrdersAds;
use common\models\search\OrdersAds as OrdersAdsSearch;
use yii\web\NotFoundHttpException;
use backend\controllers\BaseAdminController;
use common\models\Log;

/**
 * OrdersAdsController implements the CRUD actions for OrdersAds model.
 */
class OrdersAdsController extends BaseAdminController
{
    /**
     * Lists all OrdersAds models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrdersAdsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrdersAds model.
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
     * Creates a new OrdersAds model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrdersAds();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addAdminLog("orders ads[create]  ID: {$model->id}", $model->id, Log::TYPE_ORDER_ADS);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OrdersAds model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addAdminLog("orders ads[update]  ID: {$model->id}", $model->id, Log::TYPE_ORDER_ADS);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing OrdersAds model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Log::addAdminLog("orders ads[delete]  ID: {$id}", $id, Log::TYPE_ORDER_ADS);

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrdersAds model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrdersAds the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrdersAds::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
