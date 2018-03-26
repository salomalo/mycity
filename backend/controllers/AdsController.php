<?php
namespace backend\controllers;

use common\models\ProductCategory;
use common\models\Region;
use yii;
use common\models\Ads;
use backend\models\search\Ads as AdsSearch;
use yii\web\NotFoundHttpException;
use \yii\helpers\Json;
use common\models\Product;
use common\models\Log;
use common\models\City;
use common\models\File;
use yii\web\HttpException;

class AdsController extends BaseAdminController
{
    public $cacheKey = 'City_index_list_';

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
            'uploadGallery' => [
                'class' => 'common\extensions\fileUploadWidget\galleryActions\UploadGallery',
                'view' => 'update',
            ],
        ];
    }

    /**
     * Lists all Ads models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $cache = Yii::$app->cache;

        $citys = $cache->get($this->cacheKey);

        if (!$citys) {
            $citys = City::find()->select(['regId' => 'region.id', 'reg' => 'region.title', 'city.id', 'city.title', 'city."idRegion"'])
                ->leftJoin(Region::tableName(), 'region.id = city."idRegion"')
                ->groupBy(['region.id', 'city.id'])->orderBy(['region.id' => SORT_ASC])->all();

            foreach ($citys as $key => $value) {
                $citys[$key]['reg'] = $citys[$key]->region->title;
            }
            $cache->set($this->cacheKey, $citys);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'citys' => $citys
        ]);
    }

    /**
     * Creates a new Ads model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionCreate($id = '')
    {
        $model = new Ads();

        if ($id != '') {
            $model->idCategory = $id;
        }

        $action = Yii::$app->request->post('action');
        if ($model->load(Yii::$app->request->post()) && !$action) {
            if ($model->isNewRecord) {
                if (Yii::$app->request->post('Product')) {
                    foreach (Yii::$app->request->post('Product') as $key => $value)
                        $model[$key] = $value;
                }
            }
//            \Yii::$app->files->upload($model, 'gallery');

            if ($model->save()) {
                Log::addAdminLog("ads[create]  ID: {$model->_id}", $model->_id, Log::TYPE_ADS);
                return $this->redirect(['view', 'id' => (string)$model->_id]);
            }
        }

        $model->video = (is_array($model->video)) ? $model->video : [];

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Displays a single Ads model.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @internal param int $_id
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Ads model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @param string $actions
     * @return mixed
     * @throws NotFoundHttpException
     * @internal param int $_id
     */
    public function actionUpdate($id, $actions = '')
    {
        $model = $this->findModel($id);

        if ($actions == 'deleteImg') {
            \Yii::$app->files->deleteFile($model, 'image');
            $model->image = '';
            $model->save();
            Log::addAdminLog("ads[update]  ID: {$model->_id}", $model->_id, Log::TYPE_ADS);
        } else {
            $action = Yii::$app->request->post('action');
            if ($model->load(Yii::$app->request->post()) && !$action) {
                if ($model->save()) {
                    Log::addAdminLog("ads[update]  ID: {$model->_id}", $model->_id, Log::TYPE_ADS);
                    return $this->redirect(['view', 'id' => (string)$model->_id]);
                }
            }
        }
        return $this->render('update', [
            'model' => $model,
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
//        \Yii::$app->files->deleteFile($this->findModel($id), 'gallery');

        $model = $this->findModel($id);
        $model->delete();
        Log::addAdminLog("ads[delete]  ID: {$id}", $id, Log::TYPE_ADS);
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return Ads
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Ads::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMod()
    {
        $out = [];

        if (Yii::$app->request->post('depdrop_parents')) {
            $ids = Yii::$app->request->post('depdrop_parents');
            //echo \yii\helpers\BaseVarDumper::dump($ids, 10, true); //die();
            $cat_id = $ids[0];
            $com_id = $ids[1];

            if ($com_id != null) {
                $out = $this->getProdList($cat_id, $com_id);
                echo Json::encode(['output' => $out, 'selected' => '']);

                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    function getProdList($cat_id, $com_id)
    {

        $products = Product::find()->where(['idCategory' => (int)$cat_id, 'idCompany' => (int)$com_id])->all();

        $out = [];

        if (is_array($products)) {
            foreach ($products as $item) {
                $out[] = ['id' => $item['_id'], 'name' => $item['title']];
            }
        }

        return $out;
    }

    public function actionDeleteSomeImage()
    {
        if (!Yii::$app->user->identity or !Yii::$app->request->isAjax) {
            throw new HttpException(403);
        }

        $ids = Yii::$app->request->post('ids', null);

        if (!is_null($ids)) {
            $files = File::find()->where(['name' => $ids])->all();

            /** @var File $file */
            foreach ($files as $file) {
                /** @var Ads $ads */
                if ($ads = Ads::findModel($file->pidMongo)) {
                    Yii::$app->files->deleteFilesGallery($ads, 'images', [$file->name]);
                }
            }
        }

        return Json::encode(['error' => 0]);
    }

    public function actionGetCompanies()
    {
        $out = [];
        $selected  = null;
        $post = Yii::$app->request->post('depdrop_all_params');

        if (!Yii::$app->request->isAjax || !$post || empty($post['ads-idcategory'])) {
            throw new HttpException(404);
        }

        $category = ProductCategory::findOne((int)$post['ads-idcategory']);

        if (!$category) {
            throw new HttpException(404);
        }

        /** @var ProductCategory[] $categories */
        $categories = $category->children()->all();
        array_unshift($categories, $category);

        foreach ($categories as $cat) {
            foreach ($cat->getCompanies()->select(['id', 'title'])->asArray()->all() as $company) {
                $out[$company['id']] = ['name' => $company['title'], 'id' => $company['id']];
            }
        }

        echo Json::encode(['output' => $out, 'selected' => '']);
    }

}
