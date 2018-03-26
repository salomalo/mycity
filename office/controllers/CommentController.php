<?php

namespace office\controllers;

use common\models\Comment;
use common\models\File;
use office\models\search\Comment as CommentSearch;
use office\models\search\WorkVacantion as WorkVacantionSearch;
use office\models\search\WorkResume as WorkResumeSearch;
use office\models\search\Ads as AdsSearch;
use yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends DefaultController
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
     * Lists all Comment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReview(){
        $searchModel = new AdsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $adsIds = array();
        foreach ($dataProvider->models as $model){
            $adsIds[] = $model->_id;
        }

        $searchModel = new WorkVacantionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $vacontionIds = array();
        foreach ($dataProvider->models as $model){
            $vacontionIds[] = $model->id;
        }

        $searchModel = new WorkResumeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $resumeIds = array();
        foreach ($dataProvider->models as $model){
            $resumeIds[] = $model->id;
        }

        $query = Comment::find()
            ->leftJoin('business', 'business."id" = comment."pid"')
            ->leftJoin('work_vacantion', 'work_vacantion."id" = comment."pid"')
            ->leftJoin('work_resume', 'work_resume."id" = comment."pid"')
            ->where(['business."idUser"' => Yii::$app->user->identity->id, 'comment."type"' => File::TYPE_BUSINESS]);

        if (count($adsIds) > 0) {
            $query->orWhere('comment."pidMongo" = ANY(:adsIds)',
                ['adsIds' => $this->php_to_postgres_array($adsIds)]);
        }

        if (count($vacontionIds) > 0) {
            $query->orWhere('comment."pid" = ANY(:vacontionIds)  AND comment."type" =  :type_vacantion',
                [
                    'vacontionIds' => $this->php_to_postgres_array($vacontionIds),
                    'type_vacantion' => File::TYPE_WORK_VACANTION
                ]);
        }

        if (count($resumeIds) > 0) {
            $query->orWhere('comment."pid" = ANY(:resumeIds)  AND comment."type" =  :type_resume',
                [
                    'resumeIds' => $this->php_to_postgres_array($resumeIds),
                    'type_resume' => File::TYPE_RESUME
                ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $searchModel = new CommentSearch();

        return $this->render('review', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function php_to_postgres_array($phpArray)
    {
        return '{' . join(',', $phpArray) . '}';
    }

    /**
     * Displays a single Comment model.
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
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new Comment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->redirect(['index']);
        $model = new Comment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Comment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Comment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
}
