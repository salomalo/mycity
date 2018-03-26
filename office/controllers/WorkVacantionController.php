<?php

namespace office\controllers;

use common\models\Business;
use common\models\File;
use common\models\Log;
use yii;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use common\models\WorkVacantion;
use office\models\search\WorkVacantion as WorkVacantionSearch;

/**
 * WorkVacantionController implements the CRUD actions for WorkVacantion model.
 */
class WorkVacantionController extends DefaultController
{
    public $idCompany = null;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors ['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
            ],
        ];
        return $behaviors;
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['post'],
//                ],
//            ],
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        //'actions' => ['index'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//        ];
    }

    /**
     * Lists all WorkVacantion models.
     * @param $idCompany
     * @return string
     * @throws HttpException
     */
    public function actionIndex($idCompany = null)
    {
        $this->idCompany = $idCompany = (int)$idCompany;

        $searchModel = new WorkVacantionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($idCompany) {
            $dataProvider->query->andWhere(['idCompany' => (int)$idCompany]);
        }

        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();
        if (!$business) {
            return $this->render('index_full', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'business' => $business,
        ]);
    }

    /**
     * Displays a single WorkVacantion model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id, $idCompany = null)
    {
        $model = $this->findModel($id);
        
        if (\Yii::$app->user->id != $model->idUser) {
            throw new NotFoundHttpException('Not Found', '404');
        }

        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();

        return $this->render('view', [
            'model' => $model,
            'business' => $business,
        ]);
    }

    /**
     * Finds the WorkVacantion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorkVacantion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorkVacantion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new WorkVacantion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param null $idCompany
     * @return mixed
     */
    public function actionCreate($idCompany = null)
    {
        $model = new WorkVacantion();
        if($idCompany != null){
            $model->idCompany = $idCompany;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addUserLog("work vacantion[create]  ID: {$model->id}", $model->id, Log::TYPE_WORK_VACANTION);
            return $this->redirect(['view', 'id' => $model->id, 'idCompany' => $idCompany]);
        }

        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();

        return $this->render('create', [
            'model' => $model,
            'business' => $business,
        ]);
    }

    /**
     * Updates an existing WorkVacantion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $idCompany = null)
    {
        $this->idCompany = $idCompany = (int)$idCompany;

        $model = $this->findModel($id);

        if (\Yii::$app->user->id != $model->idUser){
           throw new NotFoundHttpException('Not Found','404');
        }

        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addUserLog("work vacantion[update]  ID: {$model->id}", $model->id, Log::TYPE_WORK_VACANTION);
            return $this->redirect(['view', 'id' => $model->id, 'idCompany' => $idCompany]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'business' => $business,
            ]);
        }
    }

    /**
     * Deletes an existing WorkVacantion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (\Yii::$app->user->id != $model->idUser){
           throw new NotFoundHttpException('Not Found','404');
        }
        $model->delete();
        Log::addUserLog("work vacantion[delete]  ID: {$id}", $id, Log::TYPE_WORK_VACANTION);

        return $this->redirect(['index']);
    }

}
