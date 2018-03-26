<?php

namespace office\controllers;

use common\models\Business;
use common\models\File;
use DateTime;
use common\models\Log;
use yii;
use yii\helpers\ArrayHelper;
use yii\web;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Action;
use office\models\search\Action as ActionSearch;

/**
 * ActionController implements the CRUD actions for Action model.
 */
class ActionController extends DefaultController
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
     * Lists all Action models.
     * @param $idCompany
     * @return mixed
     * @throws HttpException
     */
    public function actionIndex($idCompany)
    {
        $this->idCompany = $idCompany = (int)$idCompany;

        $searchModel = new ActionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $idCompany = (int)$idCompany;

        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();
        if (!$business) {
            throw new HttpException(404);
        }

        $dataProvider->query->andWhere(['idCompany' => $idCompany]);

        return $this->render('index', [
            'idCompany' => $idCompany,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'business' => $business,
        ]);
    }

    /**
     * Displays a single Action model.
     * @param integer $id
     * @return mixed
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionView($id, $idCompany)
    {
        $model = $this->findModel($id);

        if (!$model->companyName || ($model->companyName->idUser !== Yii::$app->user->id)) {
            throw new HttpException(403);
        }

        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();

        return $this->render('view', [
            'model' => $model,
            'business' => $business,
        ]);
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

    /**
     * Creates a new Action model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param null $idCompany
     * @return mixed
     */
    public function actionCreate($idCompany = null)
    {
        $model = new Action();

        if ($idCompany != null) {
            $model->idCompany = $idCompany;
        }

        if ($model->load(Yii::$app->request->post())) {
            $dateStart = new DateTime($model->dateStart);
            $dateEnd = new DateTime($model->dateEnd);

            $dateStart->setTime(00, 00, 00);
            $dateEnd->setTime(23, 59, 59);

            $model->dateStart = $dateStart->format('Y-m-d H:i:s');
            $model->dateEnd = $dateEnd->format('Y-m-d H:i:s');

            if ($model->save()) {
                Log::addUserLog("action[create]  ID: {$model->id}", $model->id, Log::TYPE_ACTION);
                return $this->redirect(['view', 'id' => $model->id, 'idCompany' => $idCompany]);
            }
        }

        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();

        return $this->render('create', [
            'model' => $model,
            'business' => $business,
        ]);
    }

    /**
     * Updates an existing Action model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $actions
     * @return mixed
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $actions = '', $idCompany = null)
    {
        $this->idCompany = $idCompany = (int)$idCompany;

        $model = $this->findModel($id);
        if (!$model->companyName || ($model->companyName->idUser !== Yii::$app->user->id)) {
            throw new HttpException(403);
        }

        if($actions === 'deleteImg'){
            Yii::$app->files->deleteFile($model, 'image');
            $model->image = '';
            if ($model->save()) {
                Log::addUserLog("action[update]  ID: {$model->id}", $model->id, Log::TYPE_ACTION);
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $dateStart = new DateTime($model->dateStart);
            $dateEnd = new DateTime($model->dateEnd);

            $dateStart->setTime(00, 00, 00);
            $dateEnd->setTime(23, 59, 59);

            $model->dateStart = $dateStart->format('Y-m-d H:i:s');
            $model->dateEnd = $dateEnd->format('Y-m-d H:i:s');

            if ($model->save()) {
                Log::addUserLog("action[update]  ID: {$model->id}", $model->id, Log::TYPE_ACTION);

                return $this->redirect(['view', 'id' => $model->id, 'idCompany' => $idCompany]);
            }
        }

        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();

        return $this->render('update', [
            'model' => $model,
            'business' => $business,
        ]);
    }

    /**
     * Deletes an existing Action model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (!$model->companyName || ($model->companyName->idUser !== Yii::$app->user->id)) {
            throw new HttpException(403);
        }

        $model->delete();
        Log::addUserLog("action[delete]  ID: {$id}", $id, Log::TYPE_ACTION);

        return $this->redirect(['index']);
    }

}
