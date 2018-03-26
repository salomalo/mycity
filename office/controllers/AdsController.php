<?php

namespace office\controllers;

use common\models\AdsProperty;
use common\models\Business;
use common\models\Gallery;
use common\models\ProductCategory;
use common\models\ProductCategoryCategory;
use common\models\ProductCompany;
use common\models\Profile;
use common\models\UserPaymentType;
use common\models\Log;
use yii;
use yii\db\ActiveRecord;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use common\models\Ads;
use common\models\Product;
use common\models\File;
use office\models\search\Ads as AdsSearch;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AdsController extends DefaultController
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

    public function actions()
    {
        return [
            'listcategories' => [
                'class' => 'common\extensions\NestedSelectCategory\Actions\GetCategory',
                'model' => 'common\models\ProductCategory',
            ],
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
     * Lists all Ads models.
     * @param null $idCompany
     * @return mixed
     * @throws HttpException
     */
    public function actionIndex($idCompany = null)
    {
        $this->idCompany = $idCompany = (int)$idCompany;

        $searchModel = new AdsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($idCompany) {
            $dataProvider->query->andWhere(['idBusiness' => (int)$idCompany]);
        }

        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();
        if (!$business) {
            return $this->render('index_full', [
                'searchModel' => $searchModel,
                'idCompany' => $idCompany,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'idCompany' => $idCompany,
            'dataProvider' => $dataProvider,
            'business' => $business,
        ]);
    }

    /**
     * Creates a new Ads model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @param null $idBusiness
     * @return mixed
     */
    public function actionCreate($id = '', $idBusiness = null)
    {
        $business = Business::find()->where(['id' => $idBusiness, 'idUser' => Yii::$app->user->id])->one();

        $paymentTypes = UserPaymentType::find()->where(['user_id' => Yii::$app->user->identity->id])->all();
        if (count($paymentTypes) == 0){
            return $this->render('no-payment-type', ['payments' => $paymentTypes]);
        }

        $model = new Ads();

        $model->idBusiness = $idBusiness ? $idBusiness : null;
        $model->idCategory = $id ? $id : null;

        $action = Yii::$app->request->post('action');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if (Yii::$app->request->post('Product')) {
                foreach (Yii::$app->request->post('Product') as $key => $value) {
                    $model[$key] = $value;
                }
            }

            return ActiveForm::validate($model);
        } elseif ($model->load(Yii::$app->request->post()) && !$action) {

            $productCompany = ProductCompany::findOne((int)$model->idCompany);
            //Если нету такого производителя, то создаем
            if (!$productCompany) {
                $newProductCompany = new ProductCompany();
                $newProductCompany->title = $model->idCompany;

                if ($newProductCompany->save()) {
                    $model->idCompany = $newProductCompany->id;

                    $idCategory = (int)$model->idCategory;
                    $rootCategory = ProductCategory::findOne(['id' => (int)$model->idCategory]);
                    $idCategory = (!$rootCategory->isRoot()) ? $rootCategory->parents()->one()->id : $idCategory;

                    $productCategoryCategory = new ProductCategoryCategory();
                    $productCategoryCategory->ProductCompany = $newProductCompany->id;
                    $productCategoryCategory->ProductCategory = $idCategory;

                    $productCategoryCategory->save();
                } else {
                    $model->idCompany = null;
                }
            }

            $this->updateProfile($model->contact);

            if (Yii::$app->request->post('Product')) {
                foreach (Yii::$app->request->post('Product') as $key => $value) {
                    $model[$key] = $value;
                }
            }

            if ($model->save()) {
                Log::addUserLog("ads[create]  ID: {$model->_id}", $model->_id, Log::TYPE_ADS);
                $idCompany = null;
                if (isset($model->business->id)){
                    $idCompany = $model->business->id;
                }
                if (isset($business->id)){
                    $idCompany = $business->id;
                }

                return $this->redirect(['view', 'id' => (string)$model->_id, 'idCompany' => $idCompany]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'idBusiness' => $idBusiness,
            'business' => $business,
        ]);
    }

    public function actionSaveProperty(){
        if(Yii::$app->request->isAjax){
            $data = Yii::$app->request->post();
            $idCategory = $data['idCategory'];
            $idCompany = $data['idCompany'];
            $idBusiness = $data['idBusiness'];
            $idProvider = $data['idProvider'];

            $model = AdsProperty::find()->where(['business_id' => $idBusiness, 'user_id' => Yii::$app->user->id])->one();

            if (!$model){
                $model = new AdsProperty();
                $model->user_id = Yii::$app->user->id;
                $model->business_id = $idBusiness;
            }

            $model->company_id = $idCompany;
            $model->category_id = $idCategory;
            $model->provider_id = $idProvider;

            if ($model->validate() && $model->save()){
                $response = 'success';
            } else {
                $response = 'error';
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'response' => $response,
            ];
        }
    }

    public function updateProfile($contact)
    {
        if (Yii::$app->user->isGuest) {
            throw new HttpException(404);
        }

        /** @var Profile $profile */
        $profile = Yii::$app->user->identity->profile;

        if ($contact && $profile) {
            $profile->phone = $contact;
            $profile->save();
        }
    }

    /**
     * Displays a single Ads model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id, $idCompany = null)
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
     * Updates an existing Ads model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @param string $actions
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdate($id, $actions = '', $idCompany = null)
    {
        $model = $this->findModel($id);

        if (Yii::$app->user->id !== $model->idUser){
           throw new HttpException(404);
        }
        if ($actions === 'deleteImg') {
            Yii::$app->files->deleteFile($model, 'image');
            $model->image = '';
            if ($model->save()) {
                Log::addUserLog("ads[update]  ID: {$model->_id}", $model->_id, Log::TYPE_ADS);
            }
        }
        $action = Yii::$app->request->post('action');
        $business = Business::find()->where(['id' => $idCompany, 'idUser' => Yii::$app->user->id])->one();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        } elseif ($model->load(Yii::$app->request->post()) && !$action) {
            $productCompany = ProductCompany::findOne((int)$model->idCompany);
            //Если нету такого производителя, то создаем
            if (!$productCompany) {
                $newProductCompany = new ProductCompany();
                $newProductCompany->title = $model->idCompany;

                if ($newProductCompany->save()) {
                    $model->idCompany = $newProductCompany->id;

                    $idCategory = (int)$model->idCategory;
                    $rootCategory = ProductCategory::findOne(['id' => (int)$model->idCategory]);
                    $idCategory = (!$rootCategory->isRoot()) ? $rootCategory->parents()->one()->id : $idCategory;

                    $productCategoryCategory = new ProductCategoryCategory();
                    $productCategoryCategory->ProductCompany = $newProductCompany->id;
                    $productCategoryCategory->ProductCategory = $idCategory;

                    $productCategoryCategory->save();
                } else {
                    $model->idCompany = null;
                }
            }

            $this->updateProfile($model->contact);

            if ($model->save()) {
                Log::addUserLog("ads[update]  ID: {$model->_id}", $model->_id, Log::TYPE_ADS);

                if ($idCompany){
                    return $this->redirect(['view', 'id' => (string)$model->_id, 'idCompany' => $business->id]);
                } else {
                    return $this->redirect(['view', 'id' => (string)$model->_id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'business' => $business,
            'idBusiness' => $idCompany
        ]);
    }

    /**
     * Deletes an existing Ads model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @internal param int $_id
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (\Yii::$app->user->id != $model->idUser){
           throw new NotFoundHttpException('Not Found','404');
        }

//        \Yii::$app->files->deleteFile($model, 'gallery');

        $model->delete();
        Log::addUserLog("ads[delete]  ID: {$id}", $id, Log::TYPE_ADS);
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Ads::find()->where(['_id' => $id, 'idUser' => Yii::$app->user->id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMod() {
    $out = [];

        if (Yii::$app->request->post('depdrop_parents')) {
            $ids = Yii::$app->request->post('depdrop_parents');
            //echo \yii\helpers\BaseVarDumper::dump($ids, 10, true); //die();
            $cat_id = $ids[0];
            $com_id = $ids[1];

            if ($com_id != null) {

               $out = $this->getProdList($cat_id, $com_id);

               echo Json::encode(['output'=>$out , 'selected'=>'']);
               return;
            }
        }
            echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    function getProdList($cat_id, $com_id){

        $products = Product::find()->where(['idCategory' => (int)$cat_id, 'idCompany' => (int)$com_id])->all();

        $out = [];

        if(is_array($products)){
            foreach ($products as $item){
                $out[] = ['id'=>$item['_id'], 'name'=>$item['title']];
            }
        }

        return $out;
    }

    public function actionDeleteSomeImage()
    {
        if (!Yii::$app->user->identity) throw new HttpException(403);

        $error = 0;
        $ids = Yii::$app->request->post('ids');

        $files = File::find()->where(['name' => $ids])->all();

        /**
         * @var File[] $files
         */
        foreach ($files as $file) {

            $model = new Ads;
            $model = $model->findModelByUser($file->pidMongo);

            if ($model) Yii::$app->files->deleteFilesGallery($model, 'images', [$file->name]);
        }

        return Json::encode(['error'=>$error]);
    }

    public function actionCompanies($id)
    {
        $out = $id ? ProductCompany::find()
            ->select(['id', 'text' => 'title'])
            ->where(['id' => ProductCategoryCategory::find()->select('ProductCompany')->where(['ProductCategory' => $id])])
            ->asArray()->all() : [];

        return Json::encode($out);
    }

}
