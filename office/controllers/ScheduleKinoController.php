<?php

namespace office\controllers;

use common\models\Business;
use Yii;
use common\models\ScheduleKino;
use common\models\search\ScheduleKino as ScheduleKinoSearch;
use yii\web\NotFoundHttpException;

/**
 * ScheduleKinoController implements the CRUD actions for ScheduleKino model.
 */
class ScheduleKinoController extends DefaultController
{
    public $idCompany = null;

    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * Lists all ScheduleKino models.
     * @return mixed
     */
    public function actionIndex($idCompany = null)
    {
        $searchModel = new ScheduleKinoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if($idCompany){
            $dataProvider->query->andWhere(['idCompany' => $idCompany]);
            
            $this->idCompany = $idCompany;
        }

        $business = Business::findOne($idCompany);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'idCompany' => $idCompany,
            'business' => $business,
        ]);
    }

    /**
     * Displays a single ScheduleKino model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $idCompany)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'idCompany' => $idCompany,
        ]);
    }

    /**
     * Creates a new ScheduleKino model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idCompany)
    {
        $model = new ScheduleKino();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'idCompany' => $idCompany]);
        } else {
            $business = Business::findOne($idCompany);
            return $this->render('create', [
                'model' => $model,
                'idCompany' => $idCompany,
                'business' => $business,
            ]);
        }
    }

    /**
     * Updates an existing ScheduleKino model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $idCompany)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'idCompany' => $idCompany]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ScheduleKino model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $idCompany)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index', 'idCompany' => $idCompany]);
    }

    /**
     * Finds the ScheduleKino model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScheduleKino the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScheduleKino::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
