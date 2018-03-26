<?php

namespace frontend\controllers;

use common\extensions\ViewCounter\AdsViewCounter;
use common\models\Business;
use common\models\Log;
use common\models\ProductCategoryCategory;
use common\models\ProductCompany;
use common\models\Profile;
use common\models\StarRating;
use common\models\UserPaymentType;
use Exception;
use frontend\components\traits\BusinessTrait;
use yii;
use yii\db\Exception as DbException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use frontend\behaviors\ViewBehavior;
use frontend\helpers\SeoHelper;
use common\models\Ads;
use common\models\File;
use common\models\Product;
use common\models\ProductCategory;
use common\models\ProductCustomfield;
use common\models\ProductCustomfieldValue;
use common\models\search\Ads as AdsSearch;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AdsController extends Controller
{
    use BusinessTrait;

    const IS_FILTER = 1;
    const TYPE_DROP_DOWN = 0;

    public $alias_category = '';
    public $breadcrumbs = [];
    public $id_category;
    public $model;

    public function init()
    {
        parent::init();

        $this->breadcrumbs = [
            ['label' => Yii::t('ads', 'Ads')]
        ];
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

    public function actionIndex($pid = null)
    {
        $businessId = Yii::$app->request->get('businessId');
        $this->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex']);
        $this->view->registerMetaTag(['name' => 'robots', 'content' => 'nofollow']);
        /*
         * Access
         */
        if (!($city = Yii::$app->request->city)) {
            //throw new HttpException(404);
        } elseif ($url = SeoHelper::redirectFromFirstPage()) {
            return $this->redirect($url, 301);
        }

        /*
         * var
         */
        $query = AdsSearch::find()->with(['category']);
        /** @var ProductCategory $category */
        $category = ProductCategory::find()->where(['url' => $pid])->one();

        $filter = null;
        $this->alias_category = ($category) ? $pid : '';
        $pid = ($category) ? $category->id : null;

        /*
         * If any category selected
         */
        if ($pid and !$category) {
            return Yii::$app->getResponse()->redirect(['ads/index'], 301);
        } elseif ($pid) {
            $this->id_category = $pid;
            define('CATEGORYID', $pid, true);
            if ($descendants = $category->children()->select('id')->all()) {
                $listId = ArrayHelper::merge([(int)$pid], ArrayHelper::getColumn($descendants, 'id'));
            } else {
                $listId = $pid;
            }
            $query->where(['idCategory' => $listId]);
            $selectedValues = null;
            if ($post = Yii::$app->request->post()) {
                foreach ($post as $key => $value) {
                    if ($key != '_csrf' && $value != '') {
                        $query->andWhere([$key => $value]);
                        $selectedValues[$key] = $value;
                    }
                }
            }
            $customfields = ProductCustomfield::find()
                ->where(['idCategory' => $pid, 'isFilter' => $this::IS_FILTER])
                ->all();
            if ($customfields) {
                $filter = $this->getFilter($customfields, $selectedValues);
            }
        }
        if (Yii::$app->params['SUBDOMAINID'] != 0) {
            $query = $query->andFilterWhere(['idCity' => Yii::$app->params['SUBDOMAINID']]);
        }

        if ($businessId){
            $query = $query->andWhere(['idBusiness' => (int)$businessId]);
        }

        if ($sort = Yii::$app->request->get('sort')) {
            $order = SORT_ASC;
            if (mb_substr($sort, 0, 1) === '-') {
                $order = SORT_DESC;
                $sort = mb_substr($sort, 1, mb_strlen($sort));
            }
            if (!in_array($sort, ['rating', 'views'])) {
                throw new HttpException(404);
            }

            $query->orderBy([$sort => $order]);
        }

        /*
         * Pagination
         */
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 12]);
        $pages->pageSizeParam = false;
        $query->offset($pages->offset)->limit($pages->limit);
        $models = $query->all();

        /*
         * Breadcrumbs
         */
        $breadcrumbs = [['label' => Yii::t('ads', 'Ads'), 'url' => ['ads/index']]];
        if ($pid != null) {
            $this->breadcrumbs = $this->getBreadcrumbs($breadcrumbs, $category);
        }

        /*
         * SEO
         */
        if (isset($city->title)){
            $title = ($category and $category->seo_title) ? $category->seo_title : Yii::t('ads', 'Business index page title {city}', ['city' => $city->title]);
        } else {
            $title = ($category and $category->seo_title) ? $category->seo_title : Yii::t('ads', 'Business index page');
        }

        $meta = ['title' => SeoHelper::registerTitle($this->view, $title)];

        $description = isset($city->title) ? Yii::t('ads', 'seo_description', ['cityTitle' => $city->title]) : Yii::t('ads', 'seo_description_no_city');

        if (!$category) {
            if ($pages->page === 0) {
                $meta['description'] = $description;
                $meta['keywords'] = isset($city->title) ? Yii::t('ads', 'seo_keywords', ['cityTitle' => $city->title]) : Yii::t('ads', 'seo_keywords');
            } else {
                $meta['robots'] = 'noindex, nofollow';
            }
        } else {
            if ($pages->page === 0) {
                $meta['description'] = $category->seo_description ? $this->checkSeoCity($category->seo_description) : $description;
                $meta['keywords'] = $category->seo_keywords ? $category->seo_keywords : Yii::t('ads', 'seo_keywords');
            } else {
                $meta['robots'] = 'noindex, nofollow';
            }
        }

        SeoHelper::registerAllMeta($this->view, $meta);
        SeoHelper::registerOgImage();

        $business = Business::findOne((int)$businessId);

        if ($business && (strtotime($business->due_date) > time())
            && $business->type == Business::TYPE_SHOP
            && ($business->price_type == Business::PRICE_TYPE_FULL || $business->price_type == Business::PRICE_TYPE_FULL_YEAR)) {
            $this->view->theme->pathMap = ['@app/views' => '@app/themes/shop'];
        }

        return $this->render('index', [
            'models' => $models,
            'business' => $business,
            'pages' => $pages,
            'filter' => $filter,
            'categoryTitle' => $category ? $category->title : null,
            'pid' => $category ? $category->id : null,
            'category' => $category ? $category : null,
        ]);
    }

    public function actionView($alias, $tab = null)
    {
        $this->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex']);
        $this->view->registerMetaTag(['name' => 'robots', 'content' => 'nofollow']);
        /*
         * var
         * $url[0] = _id, $url[1] = url
         */
        $url = explode('-', $alias, 2);
        $model = Ads::findModel($url[0]);

        if (!$model) {
            return Yii::$app->getResponse()->redirect(['ads/index'],301);
        } elseif (!isset($url[1]) or $model->url !== $url[1]) {
            return Yii::$app->getResponse()->redirect(['ads/' . $model->_id . '-' . $model->url],301);
        }

        $model->attachBehavior('view', [
            'class' => ViewBehavior::className(),
            'id' => $model->_id,
            'countMonth' => true
        ]);

        $this->id_category = isset($model->category->id) ? $model->category->id : 1;
        define('CATEGORYID', $this->id_category, true);

        /*
         * Breadcrumbs
         */
        $view = $this->view;
        $breadcrumbs = [['label' => Yii::t('ads', 'Ads'), 'url' => ['ads/index']]];
        $this->breadcrumbs = $this->getBreadcrumbs($breadcrumbs, $model->category, true);
        $this->breadcrumbs[] = ['label' => $model->title];

        $view->params['breadcrumbs'][] = ['label' => Yii::t('product', 'Goods'), 'url' => ['index']];
        $view->params['breadcrumbs'][] = $model->title;
        $view->params['breadcrumbs'][] = !empty($model->company) ? "{$model->company->title} {$model->model}" : $model->model;

        /*
         * SEO
         */
        $seo_description = !empty($model->seo_description) ? $model->seo_description : ArrayHelper::getValue($model->category, 'seo_description', '');
        $seo_keywords = !empty($model->seo_keywords) ? $model->seo_keywords : ArrayHelper::getValue($model->category, 'seo_keywords', '');

        SeoHelper::registerTitle($view, ArrayHelper::getValue($model, 'title', 'Объявление') . ' - CityLife');
        SeoHelper::registerAllMeta($view, ['description' => $seo_description, 'keywords' => $seo_keywords]);

        $url = $model->image ? Yii::$app->files->getUrl($model, 'image') : null;
        SeoHelper::registerOgImage($url);

        AdsViewCounter::widget(['item' => $model]);

        //находим предприятие обьявление и если оно подходит отображает в лэйауте shop
        $business = Business::findOne($model->idBusiness);

        $this->businessModel = $business;
        if ($this->businessModel) {
            $this->initTemplate();
        }

        return $this->render('view', ['model' => $model, 'tab' => $tab, 'business' => $business]);
    }

    public function actionCreate()
    {
        if (!Yii::$app->user->identity) {
            return $this->redirect('ads');
        }

        $paymentTypes = UserPaymentType::find()->where(['user_id' => Yii::$app->user->identity->id])->all();
        if (count($paymentTypes) == 0){
            return $this->render('no-payment-type');
        }

        $this->breadcrumbs = [
            ['label' => Yii::t('ads', 'Ads'), 'url' => Url::to(['ads/index'])],
            ['label' => Yii::t('ads', 'To_add_an_advert')],
        ];

        $model = new Ads();

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
            if (!$productCompany){
                $newProductCompany = new ProductCompany();
                $newProductCompany->title = $model->idCompany;

                $model->idCompany = $newProductCompany->save() ? $newProductCompany->id : null;

                $idCategory = (int)$model->idCategory;
                $rootcategory = ProductCategory::findOne(['id'=>(int)$model->idCategory]);
                $idCategory = (!$rootcategory->isRoot()) ? $rootcategory->parents()->one()->id : $idCategory;

                $productCategoryCategory = new ProductCategoryCategory();
                $productCategoryCategory->ProductCompany = $newProductCompany->id;
                $productCategoryCategory->ProductCategory = $idCategory;

                $productCategoryCategory->save();
            }

            $this->updateProfile($model->contact);

            if (Yii::$app->request->post('Product')) {
                foreach (Yii::$app->request->post('Product') as $key => $value) {
                    $model[$key] = $value;
                }
            }

            if ($model->save()) {
                Log::addAdminLog("ads[create]  ID: {$model->_id}", $model->_id, Log::TYPE_ADS);

                return $this->redirect(['view', 'alias' => $model->_id . '-' . $model->url]);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($alias)
    {
        //$url[0] - _id
        //$url[1] - url
        $url = explode('-', $alias, 2);

        if (!Yii::$app->user->identity) {
            throw new HttpException(403);
        }

        $model = new Ads();
        $model = $model->findModelByUser($url[0]);

        if (!$model) {
            throw new HttpException(403);
        } elseif (!isset($url[1]) or $model->url !== $url[1]) {
            return Yii::$app->getResponse()->redirect(['ads/' . $model->_id . '-' . $model->url]);
        }

        $this->updateProfile($model->contact);


        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        } elseif ($model->load(Yii::$app->request->post()) and $model->save()) {
            $productCompany = ProductCompany::findOne((int)$model->idCompany);
            if (!$productCompany){
                $newProductCompany = new ProductCompany();
                $newProductCompany->title = $model->idCompany;

                $model->idCompany = $newProductCompany->save() ? $newProductCompany->id : null;

                $idCategory = (int)$model->idCategory;
                $rootcategory = ProductCategory::findOne(['id'=>(int)$model->idCategory]);
                $idCategory = (!$rootcategory->isRoot()) ? $rootcategory->parents()->one()->id : $idCategory;

                $productCategoryCategory = new ProductCategoryCategory();
                $productCategoryCategory->ProductCompany = $newProductCompany->id;
                $productCategoryCategory->ProductCategory = $idCategory;

                $productCategoryCategory->save();
            }

                Log::addAdminLog("ads[update]  ID: {$model->_id}", $model->_id, Log::TYPE_ADS);
                return $this->redirect(['view', 'alias' => $model->_id . '-' . $model->url]);
        }
        $this->breadcrumbs = [
            ['label' => Yii::t('ads', 'Ads'), 'url' => Url::to(['ads/index'])],
            ['label' => Yii::t('ads', 'To_edit_advert')],
        ];

        return $this->render('update', ['model' => $model]);
    }

    public function actionRatingChange()
    {
        $value = Yii::$app->request->post('value');
        /** @var $model Ads */
        if ($id = Yii::$app->request->post('id')) {
            $model = $this->findModel($id);
        }

        if (!Yii::$app->request->isAjax || !$id || !$value || empty($model)) {
            Yii::$app->response->setStatusCode(404);

            return json_encode(['error' => 1, 'id' => $id, 'value' => $value, 'model' => !empty($model)]);
        } elseif (empty(Yii::$app->user->identity)) {
            Yii::$app->response->setStatusCode(403);

            return json_encode(['error' => 2]);
        } else {
            $history = StarRating::find()->where([
                'user_id' => Yii::$app->user->identity->id,
                'object_id' => $id,
                'object_type' => File::TYPE_ADS,
            ])->one();
            if (!$history) {
                $history = new StarRating([
                    'user_id' => Yii::$app->user->identity->id,
                    'object_id' => $id,
                    'object_type' => File::TYPE_ADS,
                    'rating' => 0,
                ]);
                $model->quantity_rating += 1;
            }
            $delta = $value - $history->rating ;
            $history->rating = $value;
            $model->total_rating += $delta;
            $model->rating = $model->total_rating / $model->quantity_rating;

            /** @var yii\db\Transaction $transaction */
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$history->save()) {
                    $error = implode(PHP_EOL, $history->firstErrors);
                    throw new DbException("Error while saving history: $error");
                }
                if (!$model->save(true, ['total_rating', 'quantity_rating', 'rating'])) {
                    $error = implode(PHP_EOL, $model->firstErrors);
                    throw new DbException("Error while saving ads: $error");
                }
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();

                Yii::$app->response->setStatusCode(500);

                return json_encode(['error' => true, 'msg' => $e->getMessage()]);
            }

            //Yii::$app->logger->rateTrigger(Ads::className(), (string)$model->_id, DetailLogObjectType::TYPE_ADS);

            return json_encode(['error' => false, 'rating' => $model->rating]);
        }
    }

    public function updateProfile($contact){
        $profile = Profile::findOne(Yii::$app->user->id);

        if (isset($contact) && $contact != '') {
            $profile->phone = $contact;

            if ((isset($profile->phone) && $profile->phone == '') || (!isset($profile->phone))) {
                $profile->save();
            }
        }
    }

    public function actionMod()
    {
        if ($ids = Yii::$app->request->post('depdrop_parents')) {
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

    public function actionDeleteSomeImage()
    {
        if (!Yii::$app->user->identity) {
            throw new HttpException(403);
        }

        $error = 0;
        $ids = Yii::$app->request->post('ids');

        $files = File::find()->where(['name' => $ids])->all();

        /**
         * @var File[] $files
         */
        foreach ($files as $file) {
            $model = new Ads;
            $model = $model->findModelByUser($file->pidMongo);

            if ($model) {
                Yii::$app->files->deleteFilesGallery($model, 'images', [$file->name]);
            }
        }

        return Json::encode(['error' => $error]);
    }

    public function actionBusinessContact(){
        if(Yii::$app->request->isAjax){
            $data = Yii::$app->request->post();
            $idProd = (string)$data['idAds'];

            /** @var Ads $ads */
            $ads = Ads::find()->where(['_id' => $idProd])->one();

            $phone = $ads ? $ads->business->phone : null;

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'phone' => $phone,
            ];
        }
    }

    public function actionCompare($id = null)
    {
        throw new HttpException(404);

        if (!$id) {
            Yii::$app->getResponse()->redirect(['ads/index']);
        }

        $query = AdsSearch::find();

        $query->where(['model' => $id]);

        if (!empty(Yii::$app->params['SUBDOMAINID'])) {
            $query = $query->andWhere(['idCity' => Yii::$app->params['SUBDOMAINID']], false);
        }

        $query->orderBy('price DESC');

        $breadcrumbs = [
            ['label' => Yii::t('ads', 'Ads'), 'url' => ['ads/index']],
            ['label' => Yii::t('ads', 'Compare_ads')]
        ];

        $this->breadcrumbs = $breadcrumbs;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 3,
            ],
        ]);

        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'defaultPageSize' => 3
        ]);
        $dataProvider->query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('compare', [
            'dataProvider' => $dataProvider,
            'pages' => $pages,
        ]);
    }

    public function getFilter($custom_fields, $selectedValues)
    {
        $value = [];
        $filter = [];

        foreach ($custom_fields as $item) {
            $title = $item['title'];
            $alias = $item['alias'];

            $cv = ProductCustomfieldValue::find()->where(['idCustomfield' => $item['id']])->all();
            if ($cv) {
                $value = ArrayHelper::map($cv, 'value', 'value');
            }

            $selVal = '';
            if ($selectedValues) {
                foreach ($selectedValues as $key => $val) {
                    if ($key == $alias) {
                        $selVal = $val;
                    }
                }
            }

            $filter[] = ['title' => $title, 'alias' => $alias, 'value' => $value, 'selVal' => $selVal];
            $value = [];
        }
        return $filter;
    }

    public function getBreadcrumbs($breadcrumbs, $category, $full = false)
    {
        if (is_object($category) and $category->isRoot()) {    // create $breadcrumbs
            $breadcrumbs[] = ['label' => $category['title'], 'url' => ['ads/index', 'pid' => $category['url']]];
        } elseif (is_object($category)) {
            $parent = $category->parents()->all();
            foreach ($parent as $item) {
                $breadcrumbs[] = ['label' => $item['title'], 'url' => ['ads/index', 'pid' => $item['url']]];
            }

            if (!$full) {
                $breadcrumbs[] = ['label' => $category['title']];
            } else {
                $breadcrumbs[] = ['label' => $category['title'], 'url' => ['ads/index', 'pid' => $category['url']]];
            }
        }
        return $breadcrumbs;
    }

    public function getProdList($cat_id, $com_id)
    {
        $products = Product::find()->select(['_id', 'title'])->where(['idCategory' => (int)$cat_id, 'idCompany' => (int)$com_id])->asArray()->all();
        $out = [];

        if (is_array($products)) {
            foreach ($products as $item) {
                $out[] = ['id' => $item['_id'], 'name' => $item['title']];
            }
        }

        return $out;
    }

    private function checkSeoCity($meta)
    {
        $result = $meta;

        if (!Yii::$app->params['SUBDOMAINTITLE']) {
            $arrDel = [' г ', ' г. ', ' города ', ' в городе ', ' м ', ' м. ', ' мiста ', ' у мiстi '];
            $arrReplace = [''];

            $result = str_replace($arrDel, $arrReplace, $result);
        }

        return $result;
    }

    protected function findModel($id)
    {
        if (($model = Ads::find()->where(['_id' => $id, 'idUser' => Yii::$app->user->identity->id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
