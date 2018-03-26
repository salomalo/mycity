<?php

namespace backend\controllers;

use Yii;
use common\models\AdsColor;
use common\models\search\AdsColor as AdsColorSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdsColorController implements the CRUD actions for AdsColor model.
 */
class AdsColorController extends BaseAdminController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['post'],
//                ],
//            ],
        ];
    }

    /**
     * Lists all AdsColor models.
     * @return mixed
     */
    public function actionIndex($adsId)
    {
        $searchModel = new AdsColorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['idAds' => $adsId]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'adsId' => $adsId
        ]);
    }

    /**
     * Displays a single AdsColor model.
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
     * Creates a new AdsColor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $adsId
     * @return mixed
     */
    public function actionCreate($adsId)
    {
        $model = new AdsColor();
        $model->idAds = $adsId;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'adsId' => $adsId,
            ]);
        }
    }

    /**
     * Updates an existing AdsColor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDeleteImg($id){
        $model = $this->findModel($id);

        Yii::$app->files->deleteFile($model, 'image');
        $model->image = '';
        $model->save();

        return $this->redirect(['update', 'id' => $model->id]);
    }

    /**
     * Deletes an existing AdsColor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $adsId = $model->idAds;
        $model->delete();

        return $this->redirect(['/ads-color/index', 'adsId' => $adsId]);
    }

    /**
     * Finds the AdsColor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdsColor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdsColor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}