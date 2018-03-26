<?php
namespace office\controllers;

use common\models\File;
use common\models\Log;
use yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use common\models\WorkResume;
use office\models\search\WorkResume as WorkResumeSearch;

/**
 * WorkResumeController implements the CRUD actions for WorkResume model.
 */
class WorkResumeController extends DefaultController
{
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
     * Lists all WorkResume models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorkResumeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WorkResume model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if (\Yii::$app->user->id != $model->idUser){
           throw new NotFoundHttpException('Not Found','404');
        }          
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the WorkResume model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorkResume the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorkResume::find()->where(['id' => $id, 'idUser' => Yii::$app->user->identity->id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new WorkResume model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param null $id
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $model = new WorkResume();
        if ($id != null) {
            $model->idCategory = $id;
        }
        if ($model->load(Yii::$app->request->post())) {
            \Yii::$app->files->upload($model, 'photoUrl');
            if ($model->save()) {
                Log::addUserLog("work resume[create]  ID: {$model->id}", $model->id, Log::TYPE_RESUME);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing WorkResume model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $actions
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $actions = '')
    {
        $model = $this->findModel($id);

        if (Yii::$app->user->id != $model->idUser){
           throw new NotFoundHttpException('Not Found','404');
        }

        if ($actions == 'deleteImg') {
            Yii::$app->files->deleteFile($model, 'photoUrl');
            $model->photoUrl = '';
            if ($model->save()) {
                Log::addUserLog("work resume[update]  ID: {$model->id}", $model->id, Log::TYPE_RESUME);
            }
        }

        if ($model->load(Yii::$app->request->post())) {

            Yii::$app->files->upload($model, 'photoUrl');

            if ($model->save()) {
                Log::addUserLog("work resume[update]  ID: {$model->id}", $model->id, Log::TYPE_RESUME);
                return $this->redirect(['view', 'id' => $model->id]);
            }

        }
        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing WorkResume model.
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

        \Yii::$app->files->deleteFile($model, 'photoUrl');
        $model->delete();
        Log::addUserLog("work resume[delete]  ID: {$id}", $id, Log::TYPE_RESUME);
        return $this->redirect(['index']);
    }

}
