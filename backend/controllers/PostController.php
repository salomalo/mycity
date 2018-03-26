<?php

namespace backend\controllers;

use common\models\City;
use yii;
use common\models\Post;
use common\models\File;
use common\models\CountViews;
use backend\models\search\Post as PostSearch;
use yii\web\NotFoundHttpException;
use common\models\Gallery;
use common\models\Log;
use common\models\Wall;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends BaseAdminController
{
    public $listAddress = [];
    
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
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
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
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param null $id
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $getCookies = Yii::$app->getRequest()->getCookies();
        
        $model = new Post();
        
        if($id != null){
            $model->idCategory = $id;
        }

        if ($model->load(Yii::$app->request->post()) and $model->save()) {
                $this->saveTegs($model->tags);
                Log::addAdminLog("post[create]  ID: {$model->id}", $model->id, Log::TYPE_POST);

                return $this->redirect(['view', 'id' => $model->id]);
        }

        $selectCity = $getCookies->getValue('SUBDOMAIN');
        if ($selectCity) {
            /** @var City $city */
            $city = City::find()->where(['subdomain' => $selectCity])->one();
            if ($city) {
                $selectCity = $city->title . ($city->region ? (', ' . $city->region->title) : '');
            }
        }

        return $this->render('create', [
            'model' => $model,
            'listAddress'=>$this->listAddress,
            'selectCity' => $selectCity ,
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
    public function actionUpdate($id, $actions = '')
    {
        $getCookies = Yii::$app->request->cookies;
        
        $model = $this->findModel($id);
        
        $model->tags = explode(', ', $model->tags);
        
        if ($actions === 'deleteImg') {
            Yii::$app->files->deleteFile($model, 'image');
            $model->updateAttributes(['image' => '']);
            Log::addAdminLog("post[update]  ID: {$model->id}", $model->id, Log::TYPE_POST);
        }

        if ($model->load(Yii::$app->request->post()) and $model->save()) {
            $this->saveTegs($model->tags);
            Log::addAdminLog("post[update]  ID: {$model->id}", $model->id, Log::TYPE_POST);

            return $this->redirect(['view', 'id' => $model->id]);
        }
        $this->listAddress = ($model->address) ?
            [['address' => $model->address, 'lat' => $model->lat, 'lon' => $model->lon, 'title'=>$model->title]] : [];

        /** @var City $city */
        $city = $model->city;
        if (!$city and ($subdomain = $getCookies->getValue('SUBDOMAIN'))) {
            $city = City::find()->where(['subdomain' => $subdomain])->one();
        }
        $selectCity = $city ? ($city->title . ($city->region ? (', ' . $city->region->title) : '')) : '';

        return $this->render('update', [
            'model' => $model,
            'listAddress'=>$this->listAddress,
            'selectCity' => $selectCity,
        ]);
    }
    
    public function actionUpdateGallery($idGallery, $idmodel)
    {
        if (!$idGallery) {
            return $this->redirect('/');
        }
        
        $model = Gallery::findOne(['id' => $idGallery]);
        
        if (!$model) {
            return $this->redirect('/');
        }
        
        if (Yii::$app->request->post('Gallery')) {
            $post = Yii::$app->request->post('Gallery');
            
            $model->updateAttributes(['title' => $post['title']]);
            return $this->redirect(['/post/update', 'id' => $idmodel]);
        }
        
        return $this->render('update-gallery', [
                'model' => $model,
            ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->files->deleteFile($this->findModel($id), 'image');
        CountViews::deleteAll(['pid'=>$id, 'type'=>File::TYPE_POST]);
        Wall::deleteAll(['pid'=>$id, 'type'=>File::TYPE_POST]);
        $this->delGallerys($id);
        $model = $this->findModel($id);
        $model->delete();
        Log::addAdminLog("post[delete]  ID: {$id}", $id, Log::TYPE_POST);

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
}
