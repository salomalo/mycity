<?php

namespace backend\controllers;

use common\models\UserPaymentType;
use yii;
use yii\web\NotFoundHttpException;
use common\models\PaymentType;
use common\models\search\PaymentType as PaymentTypeSearch;
use common\models\search\UserPaymentType as UserPaymentTypeSearch;
use common\models\Log;

/**
 * PaymentTypeController implements the CRUD actions for PaymentType model.
 */
class PaymentTypeController extends BaseAdminController
{
    /**
     * Lists all PaymentType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModelPT = new PaymentTypeSearch();
        $dataProviderPT = $searchModelPT->search(Yii::$app->request->queryParams);

        $dataProviderUPT = (new UserPaymentTypeSearch())->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModelPT' => $searchModelPT,
            'dataProviderPT' => $dataProviderPT,
            'dataProviderUPT' => $dataProviderUPT,
        ]);
    }

    /**
     * Displays a single PaymentType model.
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
     * Creates a new PaymentType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PaymentType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addAdminLog("payment type[create]  ID: {$model->id}", $model->id, Log::TYPE_PAYMENT_TYPE);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PaymentType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param null $actions
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $actions = null)
    {
        $model = $this->findModel($id);

        if ($actions === 'deleteImg') {
            Yii::$app->files->deleteFile($model, 'image');
            $model->image = '';
            $model->save();
        } elseif ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addAdminLog("payment type[update]  ID: {$model->id}", $model->id, Log::TYPE_PAYMENT_TYPE);

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing PaymentType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Log::addAdminLog("payment type[delete]  ID: {$id}", $id, Log::TYPE_PAYMENT_TYPE);

        return $this->redirect(['index']);
    }

    /**
     * Finds the PaymentType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PaymentType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaymentType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
