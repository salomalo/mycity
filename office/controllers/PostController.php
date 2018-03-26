<?php

namespace office\controllers;

use common\models\Business;
use common\models\CountViews;
use common\models\File;
use common\models\Gallery;
use common\models\Post;
use common\models\Log;
use office\models\search\Post as PostSearch;
use yii;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends DefaultController
{
    public $listAddress = [];
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
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['post'],
//                ],
//            ],
//        ];
    }
    
    public function actions()
    {
        return [
            'deleteGallery' => [
                'class' => 'common\extensions\fileUploadWidget\galleryActions\DeleteGallery',
                'view' => 'update',
            ],
            'addGallery' => [
                'class' => 'common\extensions\fileUploadWidget\galleryActions\AddGallery',
                'view' => 'update',
            ],
            'uploadGallery' => [
                'class' => 'common\extensions\fileUploadWidget\galleryActions\UploadGallery',
                'view' => 'update',
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @param null $idCompany
     * @return mixed
     * @throws HttpException
     */
    public function actionIndex($idCompany = null)
    {
        $this->idCompany = $idCompany = (int)$idCompany;

        $searchModel = new PostSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        if ($idCompany) {
            $dataProvider->query->andWhere(['business_id' => (int)$idCompany]);
        }

        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();
        if (!$business) {
            throw new HttpException(404);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'business' => $business,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id, $idCompany)
    {
        $model =  $this->findModel($id);
        
        if (\Yii::$app->user->id != $model->idUser){
           throw new NotFoundHttpException('Not Found','404');
        }

        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();

        return $this->render('view', [
            'model' => $model,
            'business' => $business,
        ]);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param null $id
     * @return mixed
     */
    public function actionCreate($idCompany, $id = null)
    {
        $model = new Post();

        if ($id != null) {
            $model->idCategory = $id;
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->idUser = Yii::$app->user->id;
            $model->status = Post::TYPE_PUBLISHED;
            $model->onlyMain = false;

            if ($model->save()) {
                Log::addUserLog("post[create]  ID: {$model->id}", $model->id, Log::TYPE_POST);
                return $this->redirect(['view', 'id' => $model->id, 'idCompany' => $idCompany]);
            }

        }

        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();

        return $this->render('create', [
            'model' => $model,
            'listAddress'=>$this->listAddress,
            'business' => $business,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $actions
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $actions = '', $idCompany = null)
    {
        $this->idCompany = $idCompany = (int)$idCompany;
        $model = $this->findModel($id);

        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();

        if (Yii::$app->user->id != $model->idUser) {
           throw new NotFoundHttpException('Not Found','404');
        }

        if ($actions == 'deleteImg') {
            $model->idUser = Yii::$app->user->id;
            Yii::$app->files->deleteFile($model, 'image');
            $model->image = '';
            if ($model->save()) {
                Log::addUserLog("post[update]  ID: {$model->id}", $model->id, Log::TYPE_POST);
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->status = Post::TYPE_PUBLISHED;
            $model->onlyMain = false;

            if ($model->save()) {
                Log::addUserLog("post[update]  ID: {$model->id}", $model->id, Log::TYPE_POST);
                return $this->redirect(['view', 'id' => $model->id, 'idCompany' => $business->id]);
            }
        }
        $this->listAddress[] = ['address' => $model->address, 'lat' => $model->lat, 'lon' => $model->lon, 'title'=>$model->title];

        return $this->render('update', [
            'model' => $model,
            'listAddress'=>$this->listAddress,
            'business' => $business,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (empty(Yii::$app->user) or Yii::$app->user->id != $model->idUser){
           throw new NotFoundHttpException('Not Found','404');
        }

        CountViews::deleteAll(['pid'=>$id, 'type'=>File::TYPE_POST]);
        $this->delGallerys($id);
        $model->delete();
        Log::addUserLog("post[delete]  ID: {$id}", $id, Log::TYPE_POST);

        return $this->redirect(['index']);
    }

    protected function delGallerys($id)
    {
        $models = Gallery::find()->where(['type' => File::TYPE_POST, 'pid' => $id])->all();

        if($models){
            foreach ($models as $gal){

                $files = File::find()->where(['type' => File::TYPE_GALLERY, 'pid' => $gal->id])->all();

                if (!empty($files)) {

                    $listFiles = [];
                    foreach ($files as $item) {

                        $listFiles[] = $item->name;

                        $item->delete();
                    }

        \Yii::$app->files->deleteFilesGallery($gal, 'attachments', $listFiles, null, $this->id . '/' . $gal->id);
                }
               $gal->delete();
            }
        }
    }

}
