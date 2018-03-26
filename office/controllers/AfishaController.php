<?php

namespace office\controllers;

use common\models\AfishaCategory;
use common\models\File;
use DateTime;
use common\models\Log;
use yii;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use common\models\Afisha;
use common\models\Business;
use office\models\search\Afisha as AfishaSearch;

/**
 * AfishaController implements the CRUD actions for Afisha model.
 */
class AfishaController extends DefaultController
{
    public $idCompany = null;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors ['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post', 'get'],
            ],
        ];
        return $behaviors;
    }

    /**
     * Lists all Afisha models.
     * @param $idCompany
     * @param bool $isFilm
     * @return mixed
     * @throws HttpException
     */
    public function actionIndex($idCompany, $isFilm = false)
    {
        /** @var AfishaSearch $searchModel */
        $searchModel = new AfishaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->idCompany = $idCompany = (int)$idCompany;

        /** @var Business $business */
        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();

        if (!$business) {
            throw new HttpException(404);
        }
        $isFilm = ($business->type == Business::TYPE_KINOTHEATER) ? true : false;

        if ($isFilm) {
            return $this->redirect(['/schedule-kino/index', 'idCompany' => $idCompany]);
        } else {
            $dataProvider->query->andWhere(['&&', 'idsCompany', "{{$idCompany}}"]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'idCompany' => $idCompany,
            'isFilm' => $isFilm,
            'business' => $business,
        ]);
    }
    
    public function actionListFilms($idCompany)
    {
        $searchModel = new AfishaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $dataProvider->query->andWhere("\"idsCompany\" && '{" . $idCompany . "}'");
        $business = Business::findOne($idCompany);
        $this->idCompany = $idCompany;
        
        return $this->render('index_film', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'idCompany' => $idCompany,
            'isFilm' => true,
            'business' => $business,
        ]);
    }

    /**
     * Displays a single Afisha model.
     * @param integer $id
     * @param $idCompany
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id, $idCompany)
    {
        $model = $this->findModel($id);
        if (!$model->isFilm) {
            if (Yii::$app->user->id != $model->companys[0]->idUser){
                throw new NotFoundHttpException('Not Found','404');
            }
        }

        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();

        return $this->render($model->isFilm ? 'view_film' : 'view', [
            'model' => $model,
            'idCompany' => $idCompany,
            'business' => $business,
        ]);
    }

    /**
     * Finds the Afisha model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Afisha the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Afisha::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new Afisha model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param null $idCompany
     * @param bool $isFilm
     * @return mixed
     */
    public function actionCreate($idCompany = null, $isFilm = false)
    {
        $model = new Afisha();

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->idCategory && !$isFilm){
                $model->idCategory = 8;
            }

            if ($model->dateStart) {
                $model->dateStart = date('Y-m-d H:i:s', strtotime($model->dateStart));
            }

            if ($model->dateEnd) {
                $model->dateEnd = date('Y-m-d H:i:s', strtotime($model->dateEnd));
            }

            if($model->save()){
                Log::addUserLog("afisha[create]  ID: {$model->id}", $model->id, Log::TYPE_AFISHA);
                return $this->redirect(['index', 'idCompany' => $idCompany]);
            }
        }

        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();
        if (!$business){
            throw new HttpException(404);
        }

        return $this->render('create', [
            'model' => $model,
            'idCompany' => $idCompany,
            'isFilm' => $isFilm,
            'business' => $business,
        ]);
    }

    /**
     * Updates an existing Afisha model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param $idCompany
     * @param string $actions
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $idCompany, $actions = '')
    {
        $model = $this->findModel($id);

        if (\Yii::$app->user->id != $model->companys[0]->idUser){
           throw new NotFoundHttpException('Not Found','404');
        }

        if ($model->dateStart) {
            $model->dateStart = date('Y-m-d', strtotime($model->dateStart));
        }

        if ($model->dateEnd) {
            $model->dateEnd = date('Y-m-d', strtotime($model->dateEnd));
        }

        if($actions == 'deleteImg'){
            \Yii::$app->files->deleteFile($model, 'image');
            $model->image = '';
            if ($model->save()) {
                Log::addUserLog("afisha[update]  ID: {$model->id}", $model->id, File::TYPE_AFISHA);
            }
            return $this->redirect(['update', 'id' => $model->id, 'idCompany' => $idCompany]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->dateStart) {
                $model->dateStart = date('Y-m-d H:i:s', strtotime($model->dateStart));
            }

            if ($model->dateEnd) {
                $model->dateEnd = date('Y-m-d H:i:s', strtotime($model->dateEnd));
            }

            if($model->save()){
                Log::addUserLog("afisha[update]  ID: {$model->id}", $model->id, Log::TYPE_AFISHA);
                return $this->redirect(['view', 'id' => $model->id, 'idCompany' => $idCompany]);
            }
        }

        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();

        return $this->render('update', [
            'model' => $model,
            'idCompany' => $idCompany,
            'business' => $business,
        ]);
    }

    /**
     * Deletes an existing Afisha model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param $idCompany
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id, $idCompany)
    {
        $model = $this->findModel($id);

        if (\Yii::$app->user->id != $model->companys[0]->idUser){
           throw new NotFoundHttpException('Not Found','404');
        }

        \Yii::$app->files->deleteFile($model, 'image');
        //\Yii::$app->files->deleteFile($this->findModel($id), 'trailer');
        $model->delete();
        Log::addUserLog("afisha[delete]  ID: {$id}", $id, Log::TYPE_AFISHA);

        return $this->redirect(['index', 'idCompany' => $idCompany]);
    }

}
