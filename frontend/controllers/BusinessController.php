<?php

namespace frontend\controllers;

use common\components\LiqPay\LiqPayCurrency;
use common\components\LiqPay\LiqPayStatuses;
use common\extensions\ViewCounter\AdsViewCounter;
use common\extensions\ViewCounter\BusinessViewCounter;
use common\helpers\SearchHelper;
use common\models\Ads;
use common\models\Afisha;
use common\models\Business;
use common\models\BusinessAddress;
use common\models\BusinessCategory;
use common\models\BusinessCustomField;
use common\models\BusinessCustomFieldDefaultVal;
use common\models\BusinessCustomFieldValue;
use common\models\BusinessProductCategory;
use common\models\Log;
use common\models\StarRating;
use common\models\ViewCount;
use frontend\components\traits\BusinessTrait;
use frontend\extensions\BlockList\BlockList;
use yii\db\Exception as DbException;
use common\models\BusinessTime;
use common\models\City as CityModel;
use common\models\File;
use common\models\Gallery;
use common\models\ProductCompany;
use common\models\LiqpayPayment;
use common\models\Profile;
use common\models\search\Business as BusinessSearch;
use common\models\search\City;
use common\models\search\ProductCategory;
use common\models\search\Region;
use common\models\Invoice;
use common\models\User;
use common\models\WorkVacantion;
use Exception;
use frontend\behaviors\ViewBehavior;
use frontend\extensions\BusinessFormatTime\BusinessFormatTime;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use frontend\helpers\SeoHelper;
use frontend\models\BusinessContact;
use HttpInvalidParamException;
use yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\Breadcrumbs;
use common\models\BusinessOwnerApplication;

/**
 * @property BusinessCategory $category
 * @property Pagination | null $pages
 * @property BusinessContact | null $modelForm
 * @property WorkVacantion | Ads | null $modelTab
 */
class BusinessController extends Controller
{
    use BusinessTrait;

    public $alias_category = '';
    public $listAddress = [];
    public $breadcrumbs = [];
    public $id_category = null;
    public $cf_values = null;

    private $pages;
    private $modelForm;
    private $modelTab;
    private $photo;

    const TAB_AFISHA = 'afisha';
    const TAB_ACTION = 'action';
    const TAB_VACANTION = 'vacantion';
    const TAB_PRODUCT = 'product';
    const TAB_PRICE = 'price';
    const TAB_FULL = null;

    public static $tabs = [
        self::TAB_AFISHA,
        self::TAB_ACTION,
        self::TAB_VACANTION,
        self::TAB_PRICE,
        self::TAB_FULL,
    ];

    public function init()
    {
        parent::init();

        $this->breadcrumbs = [['label' => Yii::t('business', 'Business')]];
    }

    public function actionIndex($pid = null)
    {
        Yii::$app->session->set('viewBusiness', 'index');
        $city = Yii::$app->request->city;

        if ($url = SeoHelper::redirectFromFirstPage()) {
            return $this->redirect($url, 301);
        }
        if (Yii::$app->request->post('pid') and !isset($this->category->id)) {
            throw new HttpException(404);
        }

        if ($city) {
            $this->businessModel = Business::find()
                ->where(['business."idCity"' => $city->id])
                ->leftJoin('business_category', 'business_category.id = ANY(business."idCategories")')
                ->andWhere(['business_category.sitemap_en' => 1]);
        } else {
            $this->businessModel = Business::find()
                ->leftJoin('business_category', 'business_category.id = ANY(business."idCategories")')
                ->andWhere(['business_category.sitemap_en' => 1]);
        }
        $this->businessModel->with('city');


        $this->alias_category = $pid;

        $this->id_category = isset($this->category->id) ? $this->category->id : null;
        if ($this->id_category and !empty($this->category->sitemap_en)) {
            define('CATEGORYID', $this->id_category, true);
            $arr = ArrayHelper::getColumn($this->category->children()->all(), 'id');
            $arr[] = $this->id_category;
            $cl = '{' . implode(',', $arr) . '}';
            $this->businessModel->andWhere(['&&', 'business."idCategories"', $cl]);
        } elseif ($this->id_category and empty($this->category->sitemap_en)) {
            throw new HttpException(404);
        }

        $sort = Yii::$app->request->get('sort');
        $count_views = '(SELECT COALESCE(SUM("view_count"."value"), 0) FROM "view_count" where ("view_count"."item_id"="business"."id")  and ("view_count"."city_id" IS NOT NULL))';

        $this->setIndexSeo();

        $this->filterCustomField();

        $countQuery = clone $this->businessModel;
        $countQuery->orderBy = null;
        $countQuery->groupBy = null;

        $subDomain = explode('.',$_SERVER['SERVER_NAME'])[0];
        $language = Yii::$app->language;
        if ($this->id_category and !empty($this->category->sitemap_en)) {
            Yii::$app->cache->set($this->id_category . '-' . $language . '-' . $subDomain . '-business-query3', $this->businessModel);
        } else {
            Yii::$app->cache->set('-' . $language . '-' . $subDomain . '-business-query3', $this->businessModel);
        }

        switch ($sort) {
            case Business::SORT_VIEWS_ASC:
                $this->businessModel->groupBy(['business.id'])
                    ->orderBy([$count_views => SORT_ASC, 'business.ratio' => SORT_DESC, 'business.id' => SORT_DESC]);

                SeoHelper::registerMetaTag($this->view, 'robots', 'noindex, nofollow');
                break;
            case Business::SORT_VIEWS_DESC:
                $this->businessModel->groupBy(['business.id'])
                    ->orderBy([$count_views => SORT_DESC, 'business.ratio' => SORT_DESC, 'business.id' => SORT_DESC]);

                SeoHelper::registerMetaTag($this->view, 'robots', 'noindex, nofollow');
                break;
            case Business::SORT_RATING_ASC:
                $this->businessModel->groupBy(['business.ratio', 'business.id'])->orderBy([
                    'COALESCE((business.total_rating / NULLIF(business.quantity_rating, 0)), 0)' => SORT_ASC,
                    'COALESCE((business.total_rating % NULLIF(business.quantity_rating, 0)), 0)' => SORT_ASC,
                    'business.ratio' => SORT_DESC,
                    'business.id' => SORT_DESC
                ]);
                SeoHelper::registerMetaTag($this->view, 'robots', 'noindex, nofollow');
                break;
            case Business::SORT_RATING_DESC:
                $this->businessModel->groupBy(['business.ratio', 'business.id'])->orderBy([
                    'COALESCE((business.total_rating / NULLIF(business.quantity_rating, 0)), 0)' => SORT_DESC,
                    'COALESCE((business.total_rating % NULLIF(business.quantity_rating, 0)), 0)' => SORT_DESC,
                    'business.ratio' => SORT_DESC,
                    'business.id' => SORT_DESC,
                ]);
                SeoHelper::registerMetaTag($this->view, 'robots', 'noindex, nofollow');
                break;
            default:
                $this->businessModel->select(['business.*', 'CASE WHEN "due_date" >= CURRENT_TIMESTAMP AND price_type != 1 THEN ratio + 100
                    ELSE ratio 
                END as new_ratio']);
                $this->businessModel->groupBy(['business.ratio', 'business.id'])->orderBy(['new_ratio' => SORT_DESC, 'business.id' => SORT_DESC]);
        }
        //убираем предприятия без категории
        $this->businessModel->andWhere(['not',['&&', 'idCategories', '{6645}']]);

        $this->pages = new Pagination(['totalCount' => $this->businessModel->count(), 'pageSize' => 10]);

        $this->pages->pageSizeParam = false;
        $this->businessModel->offset($this->pages->offset)->limit($this->pages->limit);
        $models = $this->businessModel->all();

        $this->setIndexBreadcrumbs($pid);
//        $this->timeQuery();

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) and (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
            return Json::encode(['breadcrumbs' => (string)$this->breadcrumbs, 'listAddress' => $this->listAddress]);
        }

        if (!empty($this->category)) {
            BusinessViewCounter::widget(['category' => $this->category->id]);
        }

        //$this->mapQuery();


        $views = ViewCount::find()->select(['count' => 'SUM("value")', 'item_id'])
            ->where([
                'item_id' => ArrayHelper::getColumn($models, 'id'),
                'category' => ViewCount::CAT_BUSINESS
            ])->groupBy('item_id')->indexBy('item_id')->column();

        return $this->render('index', [
            'models' => $models,
            'views' => $views,
            'pages' => $this->pages,
            'idCategory' => $this->id_category,
            'titleCategory' => ($this->category) ? $this->category->title : null,
            'pid' => $pid,
        ]);
    }

    public function actionMap($pid = null)
    {
        Yii::$app->session->set('viewBusiness', 'map');

        /** @noinspection PhpUndefinedFieldInspection */
        if (!Yii::$app->request->city) {
            throw new HttpException(404);
        }
        $this->businessModel = Business::find()->where(['idCity' => Yii::$app->params['SUBDOMAINID']])
            ->leftJoin('business_category', 'business_category.id = ANY(business."idCategories")')
            ->andWhere(['business_category.sitemap_en' => 1]);

        $this->alias_category = $pid;
        if (Yii::$app->request->post('pid') and !isset($this->category->id)) {
            throw new HttpException(404);
        }
        $this->id_category = isset($this->category->id) ? $this->category->id : null;
        if ($this->id_category) {
            define('CATEGORYID', $this->id_category, true);
            $arr = ArrayHelper::merge(
                [$this->id_category],
                ArrayHelper::getColumn($this->category->children()->all(), 'id')
            );
            $cl = '{' . implode(',', $arr) . '}';
            $this->businessModel->andWhere(['&&', 'business."idCategories"', $cl]);
        }

        $this->setMapBreadcrumbs($pid);
        $this->mapQuery();
        $this->setMapSeo($this->category);

        return $this->render('map', [
            'models' => $this->businessModel,
            'pages' => $this->pages,
            'titleCategory' => $this->category ? $this->category->title : null,
            'pid' => $pid,
        ]);
    }

    /**
     * @return string
     */
    private function getViewName()
    {
        $view = Business::$views[Business::PRICE_TYPE_FREE];
        if ($this->businessModel->isActive) {
            $view = Business::$views[$this->businessModel->price_type];
        }
        $view = 'view' . $view;
        return $view;
    }

    private function view($s = null)
    {
        $this->initTemplate();
        $this->businessModel->attachBehavior('view', [
            'class' => ViewBehavior::className(),
            'type' => File::TYPE_BUSINESS,
            'id' => $this->businessModel->id,
            'countMonth' => true,
        ]);

        $this->setViewSeo();
        $this->validateContacts();
        $this->setAddress();
        $this->setCategory();
        $this->setBreadcrumbs();

        $isShowDescription = Business::find()
            ->where(['id' => $this->businessModel->id])
            ->andWhere(['isChecked' => 1])
            ->count();

        if ($this->businessModel->load(Yii::$app->request->post())){
            $listCategory = $this->businessModel->listCategory;
        } else {
            $listCategory = null;
        }

        return $this->render($this->getViewName(), [
            'model' => $this->businessModel,
            'modelForm' => $this->modelForm,
            'modelTab' => $this->modelTab,
            'isGoods' => ($this->businessModel->getAds()->count() !== 0),
            'isAction' => ($this->businessModel->getActiveAction()->count() !== 0),
            'isAfisha' => ($this->businessModel->getActiveAfisha()->count() !== 0),
            'isShowDescription' => $isShowDescription,
            's' => $s,
            'listCategory' => $listCategory
        ]);
    }

    public function actionSearch($alias, $s){
        $url = explode('-', $alias, 2);
        $url[0] = (int)$url[0];

        if (!$url[0]) {
            throw new HttpException(404);
        }

        $this->businessModel = Business::find()->where(['id' => (int)$url[0]])->one();
        if (!$this->businessModel){
            throw new HttpException(404);
        }

        $this->setSearchBreadcrumbs($this->businessModel);
        $this->initTemplate();

        $view = $this->view;
        SeoHelper::registerTitle($view, 'Поиск ' . $s . ' - CityLife');

        $query = Ads::find()
            ->where(['idBusiness' => $this->businessModel->id])
            ->andWhere([
                'or',
                ['like', 'title', (string)$s],
                ['like', 'description', (string)$s]
            ]);
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => 12,
            'pageSizeParam' => false
        ]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('search', [
            'models' => $models,
            'business' => $this->businessModel,
            'pages' => $pages,
            'search' => $s,
        ]);
    }

    public function actionView($alias, $tab = null, $pidTab = null, $s = null)
    {
        $req = Yii::$app->request;
        $city = $req->city;
        $url = explode('-', $alias, 2);
        $url[0] = (int)$url[0];

        if (!in_array($tab, self::$tabs) or !$url[0]) {
            throw new HttpException(404);
        }

        if ($city) {
            $this->businessModel = Business::find()->where(['id' => (int)$url[0], 'idCity' => $city->id])->one();
            if ($this->businessModel === null) {
                if (isset($url[1])) {
                    $this->businessModel = Business::find()->where(['url' => $url[1], 'idCity' => $city->id])->one();
                }
                if ($this->businessModel === null) {
                    Yii::$app->session->setFlash('business_not_found', Yii::t('business', 'Business not found'));

                    return $this->redirect(['business/index'], 301);
                }
            }
        } else {
            $this->businessModel = Business::find()->where(['id' => (int)$url[0]])->one();
            if ($this->businessModel === null) {
                if (isset($url[1])) {
                    $this->businessModel = Business::find()->where(['url' => $url[1]])->one();
                }
                if ($this->businessModel === null) {
                    Yii::$app->session->setFlash('business_not_found', Yii::t('business', 'Business not found'));

                    return $this->redirect(['business/index'], 301);
                }
            }
        }

        if ($this->businessModel->city && (!$city || ($city->id !== $this->businessModel->idCity))) {
            $url = "http://{$this->businessModel->city->subdomain}." . Yii::$app->params['appFrontend'] . Url::current();

            return $this->redirect($url, 301);
        }

        if (("{$this->businessModel->id}-{$this->businessModel->url}" !== $alias) or
            ($tab === 'price' and !$this->businessModel->prices) or
            ($tab === 'vacantion' and !$this->businessModel->vacantions)
        ) {
            return $this->redirect(['view', 'alias' => "{$this->businessModel->id}-{$this->businessModel->url}"], 301);
        }

        // Галерея
        if ($req->post('idGallery')) {
            return $this->returnGallery()->render('_change_gallery', [
                'model' => $this->businessModel,
                'idGallery' => $req->post('idGallery'),
            ]);
        }

        return $this->view($s);
    }

    public function actionViewByShortUrl($short_url, $tab = null, $pidTab = null)
    {
        $req = Yii::$app->request;
        $city = $req->city;

        if (!in_array($tab, self::$tabs) or !$short_url) {
            throw new HttpException(404);
        }
        $this->businessModel = Business::find()->where(['short_url' => $short_url]);
        if (!$this->businessModel->one()){
            throw new HttpException(404);
        }

        if ($city) {
            $this->businessModel->andWhere(['idCity' => $city->id]);
        }
        $this->businessModel = $this->businessModel->one();

        if ($this->businessModel === null) {
            Yii::$app->session->setFlash('business_not_found', Yii::t('business', 'Business not found'));

            return $this->redirect(['business/index'], 301);
        }

        if ($this->businessModel->city && (!$city || ($city->id !== $this->businessModel->idCity))) {
            $url = "http://{$this->businessModel->city->subdomain}." . Yii::$app->params['appFrontend'] . Url::current();

            return $this->redirect($url, 301);
        }

        // Галерея
        if ($req->post('idGallery')) {
            return $this->returnGallery()->render('_change_gallery', [
                'model' => $this->businessModel,
                'idGallery' => $req->post('idGallery'),
            ]);
        }

        return $this->view();
    }

    public function actionTop($pid = null)
    {
        Yii::$app->session->set('viewBusiness', 'top');

        $this->businessModel = Business::find()->select(['business.*']);
        /** @noinspection PhpUndefinedFieldInspection */
        if (!Yii::$app->request->city) {
            throw new HttpException(404);
        }
        if ($url = SeoHelper::redirectFromFirstPage()) {
            return $this->redirect($url,301);
        }

        $city = Yii::$app->request->city;
        $this->businessModel = Business::find()
            ->leftJoin('business_category', 'business_category.id = ANY(business."idCategories")')
            ->where(['business."idCity"' => $city->id])
            ->andWhere(['business_category.sitemap_en' => 1]);

        $this->alias_category = $pid;

        $this->id_category = isset($this->category->id) ? $this->category->id : null;
        if ($this->id_category and !empty($this->category->sitemap_en)) {
            define('CATEGORYID', $this->id_category, true);
            $arr = ArrayHelper::getColumn($this->category->children()->all(), 'id');
            $arr[] = $this->id_category;
            $cl = '{' . implode(',', $arr) . '}';
            $this->businessModel->andWhere(['&&', '"business"."idCategories"', $cl]);
        } elseif ($this->id_category and empty($this->category->sitemap_en)) {
            throw new HttpException(404);
        }

        $this->setIndexBreadcrumbs();
        SeoHelper::registerTitle($this->view, 'Топ-100 - CityLife');
        SeoHelper::registerMetaTag($this->view, 'robots', 'noindex, nofollow');

        $this->businessModel->orderBy([
            'COALESCE(("total_rating" / NULLIF(quantity_rating, 0)), 0)' => SORT_DESC,
            'COALESCE(("total_rating" % NULLIF(quantity_rating, 0)), 0)' => SORT_DESC,
            'ratio' => SORT_DESC
        ])->limit(100);

        return $this->render('index', [
            'models' => $this->businessModel->all(),
            'pid' => $this->alias_category,
            'listAddress' => $this->listAddress,
            'isSelect' => false,
            'activeTab' => 'top',
            'top' => true,
            'idCategory' => $this->id_category,
            'titleCategory' => null,
        ]);
    }

    public function actionSelect($idModel, $s = null)
    {
        if (empty(Yii::$app->request->city))
            throw new HttpException(404);

        $model = Ads::find()->select(['idBusiness'])->where(['model' => $idModel . ''])->asArray()->all();

        $arrId = [];
        foreach ($model as $item) {
            $arrId[] = $item['idBusiness'];
        }

        $query = BusinessSearch::find();

        $search = $s;

        if ($search) {
            $query = $query->andFilterWhere(SearchHelper::getBusinessOrWhere($search));
        }

        if (!empty(Yii::$app->params['SUBDOMAINID'])) {
            $query = $query->andFilterWhere(['idCity' => Yii::$app->params['SUBDOMAINID']], false);
        }

        $query = $query->andWhere(['id' => $arrId]);

        $view = null;

        if (Yii::$app->request->post('view')) {
            $view = Yii::$app->request->post('view');
        }

        if (!$view && !Yii::$app->session->has('viewBusiness')) {
            Yii::$app->session->set('viewBusiness', 'index');
        }

        if ($view) {
            Yii::$app->session->set('viewBusiness', ($view == 'map') ? 'map' : 'index');
        }

        if (Yii::$app->session->get('viewBusiness') == 'map') {
            $model2 = $query->orderBy(['ratio' => SORT_DESC, 'id' => SORT_DESC])->limit(8400)->all();
            foreach ($model2 as $item) {
                $address = BusinessAddress::find()->where(['idBusiness' => $item['id']])
                    ->andWhere('lat > :lat', [':lat' => 0])
                    ->andWhere('lon > :lon', [':lon' => 0])
                    ->all();
                foreach ($address as $item2) {
                    //$add['address'] = $this->fixForMap($item2->address);
                    $add['phone'] = $this->fixForMap($item2->phone);
                    $add['lat'] = $this->fixForMap($item2->lat);
                    $add['lon'] = $this->fixForMap($item2->lon);
                    $add['idBusiness'] = $this->fixForMap($item2->idBusiness);
                    $this->listAddress[] = array_merge($add, [
                        'title' => $this->fixForMap($item['title']),
                        'link' => $this->fixForMap(Html::a('Подробнее', \Yii::$app->urlManager->createUrl(['business/view', 'alias' => $item['id'] . '-' . $item['url']]))),
                    ]);
                }
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['ratio' => SORT_DESC, 'business.id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render(Yii::$app->session->get('viewBusiness'), [
            'dataProvider' => $dataProvider,
            'title' => '',
            'pid' => $this->alias_category,
            'listAddress' => $this->listAddress,
            'idModel' => $idModel,
            'isSelect' => true,
            'activeTab' => Yii::$app->session->get('viewBusiness'),
        ]);
    }

    public function actionRatingChange()
    {
        $value = Yii::$app->request->post('value');
        /** @var $model Business */
        if ($id = Yii::$app->request->post('id')) {
            $model = Business::find()->where(['id' => $id])->one();
        }

        if (!Yii::$app->request->isAjax or !$id or !$value or empty($model)) {
            Yii::$app->response->setStatusCode(404);
            return json_encode(['error' => 1]);
        } elseif (empty(Yii::$app->user->identity)) {
            Yii::$app->response->setStatusCode(403);
            return json_encode(['error' => 2]);
        } else {
            $user = Yii::$app->user->identity->id;
            $history = StarRating::find()->where([
                'user_id' => $user,
                'object_id' => $id,
                'object_type' => File::TYPE_BUSINESS,
            ])->one();
            if (!$history) {
                $history = new StarRating([
                    'user_id' => $user,
                    'object_id' => $id,
                    'object_type' => File::TYPE_BUSINESS,
                    'rating' => 0,
                ]);
                $model->quantity_rating += 1;
            }
            $delta = $value - $history->rating ;
            $history->rating = $value;
            $model->total_rating += $delta;

            /** @var yii\db\Transaction $transaction */
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$history->save() || !$model->save()) {
                    throw new DbException('Error while saving rating');
                }
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
            }

            //Yii::$app->logger->rateTrigger(Business::className(), $model->id, DetailLogObjectType::TYPE_BUSINESS);

            return json_encode(['error' => false, 'rating' => $model->rating]);
        }
    }

    public function actionGoods($alias, $urlCategory = null)
    {
        if ($urlCategory == 'goods'){
            $urlCategory = null;
        }
        $url = explode('-', $alias, 2);
        $url[0] = (int)$url[0];

        if (!$url[0]) {
            throw new HttpException(404);
        }

        $this->businessModel = Business::find()->where(['id' => (int)$url[0]])->one();

        if (is_null($this->businessModel) and isset($url[1])) {
            $this->businessModel = Business::find()->where(['url' => $url[1]])->one();
        }
        if (is_null($this->businessModel)) {
            Yii::$app->session->setFlash('business_not_found', Yii::t('business', 'Business not found'));

            return $this->redirect(['/business/index'], 301);
        }

        if ("{$this->businessModel->id}-{$this->businessModel->url}" !== $alias) {
            return $this->redirect(['view', 'alias' => "{$this->businessModel->id}-{$this->businessModel->url}"], 301);
        }

        $this->setViewSeo();
        $this->setAddress();
        $this->setCategory();

        $view = Business::$views[Business::PRICE_TYPE_FREE];
        if ($this->businessModel->due_date && (time() < (strtotime($this->businessModel->due_date) + 3600*24*7)) && isset(Business::$views[$this->businessModel->price_type])) {
            $view = Business::$views[$this->businessModel->price_type];
        }

        $query = Ads::find()->where(['idBusiness' => $this->businessModel->id]);
        if ($urlCategory){
            $productCategory = ProductCategory::findOne(['url' => $urlCategory]);
            if ($productCategory){
                $childCat = ArrayHelper::getColumn($productCategory->children()->all(), 'id');
                $whereInCategory = [
                    'or',
                    ['idCategory' => $productCategory->id],
                    ['idCategory' => $childCat]
                ];
                $query->andWhere($whereInCategory);
            } else {
                throw new HttpException(404);
            }

        } else {
            $productCategory = null;
        }

        $this->initTemplate();
        $this->setGoodsBreadcrumbs($productCategory);

        $pageSize = 10;
        if (!$this->businessModel->template || $this->businessModel->template->title == 'shop'){
            $pageSize = 12;
        }

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);
        $pages->pageSizeParam = false;
        $query->offset($pages->offset)->limit($pages->limit);
        $ads = $query->all();

        return $this->render('goods', [
            'model' => $this->businessModel,
            'ads' => $ads,
            'backgroundDisplay' => $view == '_free' ? false : true,
            'isGoods' => ($this->businessModel->getAds()->count() !== 0),
            'isAction' => ($this->businessModel->getActiveAction()->count() !== 0),
            'isAfisha' => ($this->businessModel->getActiveAfisha()->count() !== 0),
            'pages' => $pages,
            'idCategory' => isset($productCategory) ? $productCategory->id : null,
        ]);
    }

    public function actionAdsView($alias, $tab = null){
        $this->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex']);
        $this->view->registerMetaTag(['name' => 'robots', 'content' => 'nofollow']);
        /*
         * var
         * $url[0] = _id, $url[1] = url
         */
        $url = explode('-', $alias, 2);
        /** @var Ads $model */
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


        $this->breadcrumbs = $this->getBreadcrumbs($model);

        /*
         * SEO
         */
        $view = $this->view;
        $seo_description = !empty($model->seo_description) ? $model->seo_description : ArrayHelper::getValue($model->category, 'seo_description', '');
        $seo_keywords = !empty($model->seo_keywords) ? $model->seo_keywords : ArrayHelper::getValue($model->category, 'seo_keywords', '');

        SeoHelper::registerTitle($view, ArrayHelper::getValue($model, 'title', 'Объявление') . ' - CityLife');
        SeoHelper::registerAllMeta($view, ['description' => $seo_description, 'keywords' => $seo_keywords]);

        $url = $model->image ? Yii::$app->files->getUrl($model, 'image') : null;
        SeoHelper::registerOgImage($url);

        AdsViewCounter::widget(['item' => $model]);

        return $this->render('ads/view', ['model' => $model, 'tab' => $tab]);
    }

    public function actionAction($alias)
    {
        $url = explode('-', $alias, 2);
        $url[0] = (int)$url[0];

        if (!$url[0]) {
            throw new HttpException(404);
        }

        $this->businessModel = Business::find()->where(['id' => (int)$url[0]])->one();

        if (is_null($this->businessModel) and isset($url[1])) {
            $this->businessModel = Business::find()->where(['url' => $url[1]])->one();
        }
        if (is_null($this->businessModel)) {
            Yii::$app->session->setFlash('business_not_found', Yii::t('business', 'Business not found'));

            return $this->redirect(['/business/index'], 301);
        }

        if ("{$this->businessModel->id}-{$this->businessModel->url}" !== $alias) {
            return $this->redirect(['view', 'alias' => "{$this->businessModel->id}-{$this->businessModel->url}"], 301);
        }

        $this->setViewSeo();
        $this->setAddress();
        $this->setCategory();
        $this->setBreadcrumbs();

        $models = $this->businessModel->getActiveAction()->all();

        $cats = [];
        foreach ($models as $action) {
            if ($action->idCategory) {
                $cats[] = $action->idCategory;
            }
        }

        $view = Business::$views[Business::PRICE_TYPE_FREE];
        if ($this->businessModel->due_date && (time() < (strtotime($this->businessModel->due_date) + 3600*24*7)) && isset(Business::$views[$this->businessModel->price_type])) {
            $view = Business::$views[$this->businessModel->price_type];
        }

        return $this->render('actions', [
            'model' => $this->businessModel,
            'actions' => $models,
            'categories' => $cats,
            'backgroundDisplay' => $view === '_free' ? false : true,
            'isGoods' => ($this->businessModel->getAds()->count() !== 0),
            'isAction' => (count($models) !== 0),
            'isAfisha' => ($this->businessModel->getActiveAfisha()->count() !== 0),
        ]);
    }

    public function actionAfisha($alias)
    {
        $url = explode('-', $alias, 2);
        $url[0] = (int)$url[0];

        if (!$url[0]) {
            throw new HttpException(404);
        }

        $this->businessModel = Business::find()->where(['id' => (int)$url[0]])->one();

        if (is_null($this->businessModel) and isset($url[1])) {
            $this->businessModel = Business::find()->where(['url' => $url[1]])->one();
        }
        if (is_null($this->businessModel)) {
            Yii::$app->session->setFlash('business_not_found', Yii::t('business', 'Business not found'));

            return $this->redirect(['/business/index'], 301);
        }

        if ("{$this->businessModel->id}-{$this->businessModel->url}" !== $alias) {
            return $this->redirect(['view', 'alias' => "{$this->businessModel->id}-{$this->businessModel->url}"], 301);
        }

        $this->setViewSeo();
        $this->setAddress();
        $this->setCategory();
        $this->setBreadcrumbs();

        /** @var Afisha[] $models */
        $models = $this->businessModel->getActiveAfisha()->all();

        $cats = [];
        foreach ($models as $afisha) {
            if ($afisha && $afisha->idCategory) {
                $cats[] = $afisha->idCategory;
            }
        }

        $view = Business::$views[Business::PRICE_TYPE_FREE];
        if ($this->businessModel->due_date && (time() < (strtotime($this->businessModel->due_date) + 3600*24*7)) && isset(Business::$views[$this->businessModel->price_type])) {
            $view = Business::$views[$this->businessModel->price_type];
        }

        return $this->render('afisha', [
            'model' => $this->businessModel,
            'afisha' => $models,
            'categories' => $cats,
            'backgroundDisplay' => $view === '_free' ? false : true,
            'isGoods' => ($this->businessModel->getAds()->count() !== 0),
            'isAction' => ($this->businessModel->getActiveAction()->count() !== 0),
            'isAfisha' => (count($models) !== 0),
        ]);
    }

    public function actionOwnerApplication($id)
    {
        if (!($user = Yii::$app->user->identity) or !($id = (int)$id)) {
            throw new HttpException(403);
        }

        $from = Yii::$app->params['supportEmail'];

        /** @var BusinessOwnerApplication $model */
        $model = BusinessOwnerApplication::find()->where([
            'user_id' => $user->id,
            'business_id' => $id,
        ])->one();

        if ($model) {
            switch ($model->status) {
                case BusinessOwnerApplication::STATUS_READY:
                    Yii::$app->mailer->compose([
                        'html' => 'application-rejected-html',
                        'text' => 'application-rejected-txt',
                    ], ['app' => $model])
                        ->setFrom($from)->setTo($user->email)
                        ->setSubject(Yii::t('app', 'Application is rejected'))
                        ->send();
                    return Yii::t('app', 'Application is rejected');
                case BusinessOwnerApplication::STATUS_OPEN:
                    return Yii::t('app', 'Email already to {email}', ['email' => $user->email]);
            }
        }

        $now = date('Y-m-d H:i:s');
        $rand = rand(1, 1000);

        $app = new BusinessOwnerApplication([
            'user_id' => $user->id,
            'business_id' => $id,
            'status' => BusinessOwnerApplication::STATUS_OPEN,
            'token' => hash('sha256', "{$user->id} {$user->email} {$id} {$now} {$rand}")
        ]);

        if ($app->save()) {
            Yii::$app->mailer->compose([
                'html' => 'application-acceptation-html',
                'text' => 'application-acceptation-txt',
            ], ['app' => $app])
                ->setFrom($from)->setTo($user->email)
                ->setSubject(Yii::t('app', 'Confirm that you are the owner {business}', ['business' => $app->business->title]))
                ->send();
            return Yii::t('app', 'Email send to {email}', ['email' => $user->email]);
        } else {
            return implode(', ', $app->firstErrors);
        }
    }

    public function actionApplicationAccept($token)
    {
        /** @var BusinessOwnerApplication $app */
        $app = BusinessOwnerApplication::find()->where(['token' => $token])->one();
        $from = Yii::$app->params['supportEmail'];

        if (!$app or !$app->business or !$app->user) {
            throw new HttpException(404);
        } elseif (BusinessOwnerApplication::countApplicationsByStatus($app->user_id, BusinessOwnerApplication::STATUS_READY) > 0) {
            Yii::$app->mailer->compose([
                'html' => 'application-rejected-html',
                'text' => 'application-rejected-txt',
            ], ['app' => $app])
                ->setFrom($from)->setTo($app->user->email)
                ->setSubject(Yii::t('app', 'Application is rejected'))
                ->send();
            throw new HttpException(403, Yii::t('app', 'Application is rejected'));
        }

        $app->business->updateAttributes(['idUser' => $app->user_id]);
        $app->updateAttributes([
            'status' => BusinessOwnerApplication::STATUS_READY,
            'token' => null,
        ]);

        Yii::$app->mailer->compose([
            'html' => 'application-completed-html',
            'text' => 'application-completed-txt',
        ], ['app' => $app])
            ->setFrom($from)->setTo($app->user->email)
            ->setSubject(Yii::t('app', 'Application is confirmed'))
            ->send();

        return $this->redirect(['/business/view', 'alias' => "{$app->business_id}-{$app->business->url}"]);
    }

    public function actionCallback($user_id, $id)
    {
        $data_post = Yii::$app->request->post('data');
        $signature = Yii::$app->request->post('signature');

        if (!$data_post or !$signature or !Yii::$app->liqPay->checkCallbackSignature($data_post, $signature)) {
            throw new HttpException(404);
        }

        $data = Json::decode(base64_decode($data_post));

        if (!empty($data['order_id'])) {
            $payment = LiqpayPayment::find()->where(['order_id' => $data['order_id']])->one();
        }

        if (empty($payment)) {
            $payment = new LiqpayPayment(['order_id' => $data['order_id']]);
        }

        if (!empty($data['action'])) {
            $payment->action = $data['action'];
        }

        if (!empty($data['status'])) {
            $payment->status = $data['status'];
        }

        $payment->data = $data_post;

        if (!$payment->save()) {
            return false;
        }

        $isSuccessPayment = !empty($data['amount']) && !empty($data['order_id']) && !empty($data['status']);
        $isSuccessPayment = $isSuccessPayment && !empty($data['status']) && ($data['status'] === LiqPayStatuses::SUCCESS);
        $isSuccessPayment = $isSuccessPayment && !empty($data['currency']) && ($data['currency'] === LiqPayCurrency::UAH);

        if (!$isSuccessPayment) {
            return false;
        }

        //"{$now}_business_{$model->id}_own_{type}_from_{$user->id}"
        $order = explode('_', $data['order_id']);

        $types = array_keys(Business::$prices);

        $isCorrectPayment = isset($order[1]) && isset($order[3]) && ((int)$order[3] === (int)$id);
        $isCorrectPayment = $isCorrectPayment && isset($order[7]) && ((int)$order[7] === (int)$user_id);
        $isCorrectPayment = $isCorrectPayment && isset($order[5]) && in_array($order[5], $types);

        $user = User::findOne($user_id);
        $business = Business::findOne($id);

        $isCorrectPayment = $isCorrectPayment && $user && $business;

        if (!$isCorrectPayment) {
            return false;
        }

        $amount = (int)$data['amount'];
        $type = (int)$order[5];
        $needlePrice = Business::$priceTypes[$type];
        $tariffs = $business->getTariffs($user);
        $duration = (isset($tariffs[$type]) && isset($tariffs[$type]['duration'])) ? $tariffs[$type]['duration'] : 1;

        if ($amount < $needlePrice) {
            throw new HttpInvalidParamException("Payment amount: $amount less then needle: $needlePrice");
        }

        $from = $business->due_date ? strtotime($business->due_date) : time();
        $to = time() + 2592000 * $duration;

        $transaction = new Invoice([
            'user_id' => (int)$user_id,
            'object_type' => File::TYPE_BUSINESS,
            'object_id' => $id,
            'paid_from' => $from,
            'paid_to' => $to,
        ]);

        if (!$transaction->save()) {
            return false;
        }

        $business->price_type = $type;
        $business->idUser = $user_id;
        $business->due_date = date('Y-m-d H:i:s', $to);
        $business->save();

        Log::addUserLog("Оплата предприятия  ID: {$payment->id}", $payment->id, Log::TYPE_PAYMENT);

        Yii::$app->mailer->compose(['html' => 'invoice-html'], [
            'description' => empty($data['description']) ? 'Оплата управления предприятием' : $data['description'],
            'price' => $amount,
            'from' => date('H:i d.m.Y', $from),
            'to' => date('H:i d.m.Y', $to),
            'business_title' => $business->title,
        ])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($user->email)
            ->setSubject('CityLife. Оплата за услугу принята.')
            ->send();

        Yii::$app->session->setFlash('success', 'successfully paid invoice');

        return $this->redirect(Url::to(['/business/success-paid']));
        //return true;
    }

    /*
     * Index methods
     * -----------------------------------------------------------------------------------------------------------------
     */

    public function actionSuccessPaid(){
        return $this->render('success-paid');
    }

    private function filterCustomField()
    {
        $this->cf_values = Yii::$app->request->get('attr');
        if (!empty($this->cf_values) and is_array($this->cf_values)) {
            $ids = ['values_id' => [], 'default_values_id' => []];

            foreach ($this->cf_values as $id => $value_id) {
                if (!empty($value_id) and ($attrObj = BusinessCustomField::findOne($id))) {
                    $this->getSubQueryId($attrObj, $value_id, $ids);
                }
                ArrayHelper::getValue($value_id, 'from', '');
                if (($value_id === '') or ((ArrayHelper::getValue($value_id, 'from') === '') and (ArrayHelper::getValue($value_id, 'to') === ''))) {
                    unset($this->cf_values[$id]);
                }
            }
            if (!empty($ids['values_id']) or !empty($ids['default_values_id'])) {
                $subQueryAttr = BusinessCustomFieldValue::find()->select('business_id')->distinct()->where(['id' => $ids['values_id']])->orWhere(['value_id' => $ids['default_values_id']]);
                $this->businessModel->andWhere(['business.id' => $subQueryAttr]);
            }
        }
    }

    private function getSubQueryId($attrObj, $value_id, &$ids)
    {
        $default_values_id = [];
        $values_id = [];

        switch ($attrObj->filter_type) {
            case BusinessCustomField::FILTER_SELECT:
                $value_id = (int)$value_id;
                $attrObj->hasDefault ? $default_values_id = [$value_id] : $values_id = [$value_id];
                break;

            case BusinessCustomField::FILTER_SINGLE_INPUT:
                if ($attrObj->hasDefault) {
                    $default_values_id = BusinessCustomFieldDefaultVal::find()->select('id')->where(['~~*', 'value', $value_id])->column();
                } else {
                    $values_id = BusinessCustomFieldValue::find()->select('id')->where(['~~*', 'value', $value_id])->column();
                }
                break;

            case BusinessCustomField::FILTER_DOUBLE_INPUT:
                $from = (float)ArrayHelper::getValue($value_id, 'from');
                $to = (float)ArrayHelper::getValue($value_id, 'to');
                if ($attrObj->hasDefault) {
                    $default_values_id = BusinessCustomFieldDefaultVal::find()->select('id')->where(['between', 'value_numb', $from, $to])->column();
                } else {
                    $values_id = BusinessCustomFieldValue::find()->select('id')->where(['between', 'value_numb', $from, $to])->column();
                }
                break;

            case BusinessCustomField::FILTER_CHECKBOXES:
                if ($attrObj->hasDefault) {
                    $default_values_id = $value_id;
                } else {
                    $values_id = $value_id;
                }
                break;
        }
        $this->addNewId($values_id, $default_values_id, $ids);
    }

    private function addNewId($values_id, $default_values_id, &$ids)
    {
        if (is_array($values_id)) {
            foreach ($values_id as $id) {
                $ids['values_id'][] = (int)$id;
            }
        }
        if (is_array($default_values_id)) {
            foreach ($default_values_id as $id) {
                $ids['default_values_id'][] = (int)$id;
            }
        }
    }

    /**
     * @return BusinessController
     */
    private function timeQuery()
    {
        if (Yii::$app->request->post('start_time') and Yii::$app->request->post('end_time') and Yii::$app->request->post('weekDay')) {
            $start_time = Yii::$app->request->post('start_time');
            $end_time = Yii::$app->request->post('end_time');
            $weekDay = Yii::$app->request->post('weekDay');
            $this->businessModel = $this->getTimeFilter($this->businessModel, $start_time, $end_time, $weekDay);
        }
        return $this;
    }

    /*
     * View methods
     * -----------------------------------------------------------------------------------------------------------------
     */
    /**
     * @param string $str
     * @return string
     */
    protected function fixForMap($str)
    {
        return str_replace(['\\', '"', "'",  "\t", chr(9)], ['', '\"', '\"', '', ''], $str);
    }

    /**
     * @return $this
     */
    private function returnGallery()
    {
        /* @var $gal Gallery */
        $gal = Gallery::findOne(['id' => Yii::$app->request->post('idGallery')]);
        $this->businessModel = Business::findOne(['id' => $gal->pid]);
        return $this;
    }

    /**
     * @param null|string $tab
     * @return $this
     */
    private function setViewSeo($tab = null)
    {
        $view = $this->view;

        $url = $this->businessModel->image ? Yii::$app->files->getUrl($this->businessModel, 'image') : null;
        SeoHelper::registerOgImage($url);

        $lang_config = ['business_title' => $this->businessModel->title, 'city_title' => Yii::$app->params['SUBDOMAINTITLE']];

        switch ($tab) {
            case self::TAB_AFISHA:
                $view->title = $this->t('Afisha_in_{business_title}{city_title}', $lang_config);
                $desc = $this->t('Afisha_in_desc_{business_title}{city_title}', $lang_config);
                $key = $this->t('Afisha_in_key_{business_title}{city_title}', $lang_config);
                break;
            case self::TAB_ACTION:
                $view->title = $this->t('Action_in_{business_title}{city_title}', $lang_config);
                $desc = $this->t('Action_in_desc_{business_title}{city_title}', $lang_config);
                $key = $this->t('Action_in_key_{business_title}{city_title}', $lang_config);
                break;
            default:
                $seo = $this->getSeo($this->businessModel, true);
                SeoHelper::registerTitle($view, $seo['title']);
                $desc = $seo['description'];
                $key = $seo['keywords'];
        }
        $view->registerMetaTag(['name' => 'title', 'content' => $view->title]);
        $view->registerMetaTag(['name' => 'description', 'content' => $desc]);
        //$view->registerMetaTag(['name' => 'keywords', 'content' => $key]);

        return $this;
    }

    /**
     * @return $this|Response
     */
    private function validateContacts()
    {
        $this->modelForm = new BusinessContact();
        if ($this->modelForm->load(Yii::$app->request->post()) and $this->modelForm->validate()) {
            return $this->refresh();
        }
        return $this;
    }

    /**
     * @return $this
     */
    private function setCategory()
    {
        foreach ($this->businessModel->idCategories as $catId) {
            if (!defined('CATEGORYID')) {
                $category = BusinessCategory::findOne($catId);
                if ($category) {
                    $this->id_category = $catId;
                    define('CATEGORYID', $catId, true);
                }
            }
        }
        return $this;
    }

    private function getBreadcrumbs($model)
    {
        $alias = "{$model->business->id}-{$model->business->url}";

        if (isset(Yii::$app->request->city->title_ge)){
            $breadcrumbs = [['label' => Yii::t('business', 'Business_guide', ['city' => Yii::$app->request->city->title_ge]), 'url' => Url::to(['business/index'])]];
        } else {
            $breadcrumbs = [['label' => Yii::t('business', 'Business_guide_no_city'), 'url' => Url::to(['business/index'])]];
        }
        $breadcrumbs[] = ['label' => $model->business->title, 'url' => ['/business/view', 'alias' => $alias]];
        $breadcrumbs[] = ['label' => $model->title];

        return $breadcrumbs;
    }

    /**
     * @return $this
     */
    private function setGoodsBreadcrumbs($productCategory){
        $alias = "{$this->businessModel->id}-{$this->businessModel->url}";
        $this->breadcrumbs = [['label' => $this->businessModel->title, 'url' => ['/business/view', 'alias' => $alias]]];

        if ($productCategory){
            $this->breadcrumbs[] = ['label' => 'Товары', 'url' => ['/business/goods', 'alias' => $alias, 'urlCategory' => 'goods']];
            $this->breadcrumbs[] = ['label' => $productCategory->title];
        } else {
            $this->breadcrumbs[] = ['label' => 'Товары'];
        }

        $this->getView()->params['breadcrumbs'][] = ['label' => 'Businesses', 'url' => ['index']];
        $this->getView()->params['breadcrumbs'][] = $this->businessModel->title;
        return $this;
    }

    /**
     * @return $this
     */
    private function setBreadcrumbs()
    {
        if ($this->businessModel->isActive && in_array($this->businessModel->price_type, [Business::PRICE_TYPE_FULL, Business::PRICE_TYPE_FULL_YEAR]) && $this->businessModel->type == Business::TYPE_SHOP) {
            $alias = "{$this->businessModel->id}-{$this->businessModel->url}";
            $this->breadcrumbs = [
                [
                    'label' => $this->businessModel->title,
                    'url' => Url::to(['/business/view', 'alias' => $alias])
                ]
            ];

            $this->breadcrumbs[] = ['label' => 'Главная'];
            return $this;
        } else {
            if (isset(Yii::$app->request->city->title_ge)) {
                $this->breadcrumbs = [['label' => Yii::t('business', 'Business_guide', ['city' => Yii::$app->request->city->title_ge]), 'url' => Url::to(['business/index'])]];
            } else {
                $this->breadcrumbs = [['label' => Yii::t('business', 'Business_guide_no_city'), 'url' => Url::to(['business/index'])]];
            }

            /** @var BusinessCategory $category */
            $category = BusinessCategory::findOne($this->id_category);
            /** @var BusinessCategory[] $parents */
            $parents = $category->parents()->all();
            /** @var BusinessCategory $item */
            foreach ($parents as $item) {
                $this->breadcrumbs[] = ['label' => $item->title, 'url' => Url::to(['business/index', 'pid' => $item->url])];
            }
            $this->breadcrumbs[] = ['label' => $category->title, 'url' => Url::to(['business/index', 'pid' => $category->url])];
            $this->breadcrumbs[] = ['label' => $this->businessModel->title];
            $this->getView()->params['breadcrumbs'][] = ['label' => 'Businesses', 'url' => ['index']];
            $this->getView()->params['breadcrumbs'][] = $this->businessModel->title;
            return $this;
        }
    }

    /**
     * @param $tab
     * @param $pidTab
     * @return $this
     */
    private function setTab($tab, $pidTab)
    {
        if ($pidTab) {
            switch ($tab) {
                case 'vacantion':
                    $this->modelTab = WorkVacantion::find()->where(['id' => $pidTab])->one();
                    break;

                case 'product':
                    $this->modelTab = Ads::find()->where(['_id' => (string)$pidTab])->one();
                    break;
            }
        }
        return $this;
    }

    /**
     * @return $this
     */
    private function setAddress()
    {
        /** @var BusinessAddress[] $address */
        $address = BusinessAddress::findAll(['idBusiness' => $this->businessModel->id]);
        $business = Business::findOne($this->businessModel->id);
        $image = $business->image ? $this->fixForMap(\Yii::$app->files->getUrl($business, 'image', 165)) : false;
        $b_working_time = $this->fixForMap(BusinessFormatTime::widget(['id' => $this->businessModel->id, 'format' => false]));
        foreach ($address as $adr) {
            $working_time = empty($adr->working_time) ? $b_working_time : $this->fixForMap($adr->working_time);
            $this->listAddress[] = [
                'id' => $adr->id,
                'idBusiness' => $adr->idBusiness,
                'lat' => $adr->lat,
                'lon' => $adr->lon,
                'working_time' => $working_time,
                'address' => $this->fixForMap($adr->address),
                'phone' => $this->fixForMap($adr->phone),
                'image' => $image,
                'title' => $this->fixForMap($this->businessModel->title),
                'hrefLink' => \Yii::$app->urlManager->createUrl([
                    'business/view',
                    'alias' => $business->id . '-' . $business->url,
                ]),
                'link' => $this->fixForMap($adr->phone),
            ];
        }
        return $this;
    }

    private function createImageUrl($model)
    {
        $this->photo = Yii::$app->files->getUrl($model, 'image', 500);
        if ($this->photo) {
            $this->photo = explode('files1q', $this->photo, 2)[1];
            $url = Url::to('image') . $this->photo;
            if ($this->photo) $this->getView()->registerMetaTag(['property' => 'og:image', 'content' => $url]);
        }
    }

    /*
     * Other methods
     * -----------------------------------------------------------------------------------------------------------------
     */
    /**
     * @param $key string
     * @param $params array
     * @return string
     */
    private function t($key, $params = [])
    {
        return Yii::t('business', $key, $params);
    }

    public function checkAlias($idUrl, $modelUrl, $alias)
    {
        $aliasFromUrl = str_replace($idUrl . '-', '', $alias);
        if ($modelUrl != $aliasFromUrl) {
            $this->redirect($idUrl . '-' . $modelUrl, 301);
        }
    }

    public function trimTime($time)
    {
        $t = explode(':', $time);
        return $t[0] . ':' . $t[1];
    }

    public function getTimeFilter($query, $start_time, $end_time, $weekDay)
    {

        $arr = [];
        //$listId = '(0)';
        $model = false;

        if ($start_time == '00:00' && $end_time == '00:00') {
            return $query;
        }

        $list = BusinessTime::find()->select(['idBusiness']);

        if ($start_time < $end_time) {
            $list = $list->where(' CASE  WHEN "start" < "end" THEN "start" <= :start AND "end" >= :end '
                . ' WHEN "start" > "end" THEN "start" <= :start AND "start" <= :end '
                . ' END', [':start' => $start_time, ':end' => $end_time]);
        }

        if ($start_time > $end_time) {
            $list = $list->where(' CASE  WHEN "start" > "end" THEN "start" <= :start AND "end" >= :end '
                //. ' WHEN "start" > "end" THEN "start" <= :start AND "start" <= :end '
                . ' END', [':start' => $start_time, ':end' => $end_time]);
        }

        if ($weekDay > 0) {
            $list = $list->andWhere('"weekDay" = :weekDay', [':weekDay' => $weekDay]);
        }

        $model = $list->all();

        if ($model) {
            foreach ($model as $item) {
                $arr[] = $item->idBusiness;
            }
            //$listId = '('.implode(',', $arr).')';
        }
        $query = $query->andWhere(['id' => $arr]);

        return $query;
    }

    public function actionCategoryList()
    {
        $pid = NULL;
        if (Yii::$app->request->post("pid")) {
            $pid = Yii::$app->request->post("pid");
        }
        $where = [];
        if ($searchText = Yii::$app->request->post("search")) {
            $where = SearchHelper::getILikeString($searchText, 'title');
        }
        if (!$pid) {
            /** @var BusinessCategory|ActiveQuery $categorylist */
            $categorylist = BusinessCategory::find()->select(['id', 'title'])->roots()->andWhere($where)->orderBy('title')->all();
        } else {
            /** @var BusinessCategory $category */
            $category = BusinessCategory::find()->where(['id' => $pid])->one();
            $categorylist = $category->children(1)->andWhere($where)->orderBy('title')->all();
        }
        echo Json::encode($categorylist);
    }

    public function actionProductCategoryList()
    {
        $pid = null;
        $where = [];
        if ($searchText = Yii::$app->request->post("search")) {
            $where = SearchHelper::getILikeString($searchText, 'title');
        }
        if (Yii::$app->request->post("pid")) {
            $pid = Yii::$app->request->post("pid");
            $root = ProductCategory::findOne(['id' => $pid]);
            $categorylist = $root->children(1)->select(['id', 'title'])->andWhere($where)->orderBy('title')->all();
        } else {
            $categorylist = ProductCategory::find()->select(['id', 'title'])->where($where)->roots()->orderBy('title')->all();
        }
        $parent = 0;
        if (($pid = Yii::$app->request->post("pid")) and $root = ProductCategory::findOne(['id' => $pid])) {
            $parent = $root->parents(1)->one();
            $parent = isset($parent->id) ? $parent->id : $parent;
        }
        echo Json::encode(['items' => $categorylist, 'back_id' => (int)$parent]);
    }

    public function actionCityList()
    {
        $pid = NULL;
        $categorylist = [];
        $where = [];
        if ($searchText = YII::$app->request->post("search")) {
            $where = SearchHelper::getILikeString($searchText, 'title');
        }
        if (YII::$app->request->post("pid")) {
            $pid = YII::$app->request->post("pid");
            $old = YII::$app->request->post("old");
            if (empty($old) || $searchText || ($searchText == '')) {
                $categorylist = City::find()->select(['id', 'title'])->where(['idRegion' => $pid])->andWhere($where)->orderBy('title')->all();
            }
        } else {
            $categorylist = Region::find()->select(['id', 'title'])->where($where)->orderBy('title')->all();
        }
        echo Json::encode($categorylist);
    }

    public function actionCityBusinessList()
    {
        $pid = NULL;

        $where = [];
        if ($searchText = Yii::$app->request->post("search")) {
            $where = SearchHelper::getILikeString($searchText, 'title');
        }
        if (Yii::$app->request->post("pid")) {
            $pid = Yii::$app->request->post("pid");
            /** @var BusinessCategory $root */
            $root = ProductCategory::findOne(['id' => $pid]);
            $categorylist = $root->children(1)->select(['id', 'title'])->andWhere($where)->orderBy('title')->all();
        } else {
            /** @var BusinessCategory[] $categorylist */
            $categorylist = ProductCategory::find()->select(['id', 'title'])->where($where)->roots()->orderBy('title')->all();
        }
        echo Json::encode($categorylist);
    }

    public function actionCityByBusiness()
    {
        if (!Yii::$app->request->isAjax) throw new HttpException(403);
        $out = [];
        $selected = '';
        if (isset($_POST['depdrop_parents'])) {
            $parent = $_POST['depdrop_parents'][0];
            if (!empty($parent)) {
                #Предприятие по id
                /* @var Business $business */
                $business = Business::findOne(['id' => $parent]);

                #Город по id
                $cities = CityModel::find()->where(['id' => $business->idCity])->select(['id', 'title'])->all();
                $cities = ArrayHelper::map($cities, 'id', 'title');

                #Преобразуем к виду [['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],[...],...]
                foreach ($cities as $id => $name) $out[] = ['id' => $id, 'name' => $name];
                $selected = $out[0]['id'];
            } else {
                $cities = CityModel::find()->where(['main' => City::ACTIVE])->select(['id', 'title'])->all();
                $cities = ArrayHelper::map($cities, 'id', 'title');
                foreach ($cities as $id => $name) $out[] = ['id' => $id, 'name' => $name];
            }
        } else {
            $cities = CityModel::find()->where(['main' => City::ACTIVE])->select(['id', 'title'])->all();
            $cities = ArrayHelper::map($cities, 'id', 'title');
            foreach ($cities as $id => $name) $out[] = ['id' => $id, 'name' => $name];
        }
        echo Json::encode(['output' => $out, 'selected' => (string)$selected]);
    }

    public function actionContact()
    {
        if (!Yii::$app->request->isAjax) {
            throw new HttpException(404);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        $phone = '';

        $idBusiness = Yii::$app->request->post('idBusiness');
        $business = $idBusiness ? Business::findOne($idBusiness) : null;

        if ($business && $business->phone) {
            $phone = $business->phone;
        } else {
            $profile = Profile::findOne(Yii::$app->user->id);

            if ($profile && $profile->phone){
                $phone = $profile->phone;
            }
        }

        return ['contact' => $phone];
    }

    public function actionAjaxBreadcrumbs()
    {
        $breadcrumbs = [];

        if (isset($_POST) && isset($_POST['breadcrumbs'])) {
            $breadcrumbs = $_POST['breadcrumbs'];
        }

        return Breadcrumbs::widget([
            'homeLink' => null,
            'links' => $breadcrumbs,
        ]);
    }

    public function actionAjaxMenu()
    {

        $pid = null;

        if (isset($_POST) && isset($_POST['pid'])) {
            $pid = $_POST['pid'];
        }

        return BlockList::widget([
            'title' => 'Business Categories',
            'className' => 'common\models\BusinessCategory',
            'attribute' => 'pid',
            'p' => $pid,
        ]);
    }

    private function getSeo($model, $isFull = false, $page = null)
    {
        $arr = [];

        if (!$model) {
            $arr['title'] = Yii::t('business', 'seo_title', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            if ($page === null) {
                $arr['keywords'] = Yii::t('business', 'seo_keywords', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
                $arr['description'] = Yii::t('business', 'seo_description', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            } else $arr['robots'] = 'noindex, nofollow';
        }

        if ($model && !$isFull) {
            $arr['title'] = $model->seo_title ? $model->seo_title :
                Yii::t('business', 'seo_title', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            if ($page === null) {
                $arr['description'] = $model->seo_description ? $model->seo_description :
                    Yii::t('business', 'seo_description', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
                $arr['keywords'] = $model->seo_keywords ? $model->seo_keywords :
                    Yii::t('business', 'seo_keywords', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            } else {
                $arr['robots'] = 'noindex, nofollow';
            }
        }

        if ($model && $isFull) {
            $arr['title'] = ($model->seo_title) ? $model->seo_title :
                Yii::t('business', 'seo_title_full', [
                    'title' => $model->title,
                    'cityTitle' => Yii::$app->params['SUBDOMAINTITLE'],
                ]);

            $arr['description'] = ($model->seo_description) ?
                $model->seo_description :
                Yii::t('business', 'seo_description_full', [
                    'title' => $model->title,
                    'cityTitle' => Yii::$app->params['SUBDOMAINTITLE'],
                ]);

            $arr['keywords'] = ($model->seo_keywords) ?
                $model->seo_keywords :
                Yii::t('business', 'seo_keywords_full', [
                    'title' => $model->title,
                    'cityTitle' => Yii::$app->params['SUBDOMAINTITLE'],
                ]);
        }

        return $arr;
    }

    /**
     * @return null|BusinessCategory
     */
    public function getCategory()
    {
        return BusinessCategory::findOne(['url' => $this->alias_category]);
    }

    /*
     * General methods
     * -----------------------------------------------------------------------------------------------------------------
     */

    /**
     * @param ActiveQuery $model
     * @param $limit
     */
    public function setListAddress(ActiveQuery $model, $idCategory = null)
    {
        $model->with('businessTimes', 'address');
        foreach ($model->batch() as $b) {
            /** @var Business $item */
            foreach ($b as $item) {
                $business_working_time = $this->fixForMap(BusinessFormatTime::widget([
                    'id' => $item->id,
                    'format' => false,
                    'models' => $item->businessTimes,
                ]));
                /** @var BusinessAddress $address */
                foreach ($item->address as $address) {
                    $working_time = empty($address->working_time) ?
                        $business_working_time : $this->fixForMap($address->working_time);
                    $this->listAddress[] = [
                        'address' => $this->fixForMap($address->address),
                        'phone' => $this->fixForMap($address->phone),
                        'working_time' => $working_time,
                        'lat' => $this->fixForMap($address->lat),
                        'lon' => $this->fixForMap($address->lon),
                        'idBusiness' => $this->fixForMap($address->idBusiness),
                        'title' => $this->fixForMap($item->title),
                        'image' => $item->image ? $this->fixForMap(\Yii::$app->files->getUrl($item, 'image', 165)) : false,
                        'hrefLink' => \Yii::$app->urlManager->createUrl([
                            'business/view',
                            'alias' => $item->id . '-' . $item->url,
                        ]),
                        'link' => $this->fixForMap(
                            Html::a('Подробнее', \Yii::$app->urlManager->createUrl([
                                'business/view',
                                'alias' => $item->id . '-' . $item->url,
                            ]))
                        ),
                    ];
                }
            }
        }

        $subDomain = explode('.',$_SERVER['SERVER_NAME'])[0];
        $language = Yii::$app->language;
        $idCategory = isset($idCategory) ? $idCategory : '';
        Yii::$app->cache->set($idCategory . '-' . $language . '-' . $subDomain . '-business-list-address3', $this->listAddress, 60 * 60 * 24);
    }

    /**
     * @param $limit int
     * @return $this
     */
    private function mapQuery($limit = 200)
    {
        $action = Yii::$app->controller->action->id;
        if (Yii::$app->session->get('viewBusiness') === 'map' || Yii::$app->session->get('viewBusiness') === 'index') {
            $this->businessModel->select(['business.id', 'business.title', 'business.url', 'business.image']);
            switch ($action) {
                case 'map':
                    $this->businessModel->with('address');
                    break;
                case 'top':
                    $this->businessModel->joinWith(['address', 'countView'])->orderBy('count_views.count DESC');
                    break;
            }
            $this->businessModel->limit($limit);
            $this->setListAddress($this->businessModel);
        }
        return $this;
    }

    /**
     * @param $idCategory string
     * @param $limit int
     * @return $this
     */
    private function mapQueryForAjax($idCategory, $limit = 200)
    {
        $action = Yii::$app->controller->action->id;
        $sql_new_ratio = 'CASE WHEN "due_date" >= CURRENT_TIMESTAMP AND price_type != 1 THEN ratio + 100
            ELSE ratio 
       END as new_ratio';
        if (Yii::$app->session->get('viewBusiness') === 'map' || Yii::$app->session->get('viewBusiness') === 'index') {
            $this->businessModel->select([
                'business.id',
                'business.title',
                'business.url',
                'business.image',
                'business.title',
                'business.url',
                'business.image',
                $sql_new_ratio
            ]);
            $this->businessModel->orderBy(['new_ratio' => SORT_DESC, 'business.id' => SORT_DESC]);
            switch ($action) {
                case 'map':
                    $this->businessModel->with('address');
                    break;
                case 'top':
                    $this->businessModel->joinWith(['address', 'countView'])->orderBy('count_views.count DESC');
                    break;
            }
            $this->businessModel->limit($limit);
            $this->setListAddress($this->businessModel, $idCategory);
        }
        return $this;
    }

    public function actionListAddress(){
        if(Yii::$app->request->isAjax){
            $data = Yii::$app->request->post();

            $idCategory = isset($data['id_category']) ? $data['id_category'] : '';
            $subDomain = explode('.',$_SERVER['SERVER_NAME'])[0];
            $language = Yii::$app->language;

            //если в кеше есть ActiveQuery $query запроса
            if (Yii::$app->cache->exists($idCategory . '-' . $language . '-' . $subDomain . '-business-query3')){
                $this->businessModel  = Yii::$app->cache->get($idCategory . '-' . $language . '-' . $subDomain . '-business-query3');

                //если в кеше остался список предприятий, то возврщаем иначе ищем и возвращаем
                $cacheKey = $idCategory . '-' . $language . '-' . $subDomain . '-business-list-address3';

                if (Yii::$app->cache->exists($cacheKey)) {
                    $status = 'data exist in cache';
                    $result = Json::encode(Yii::$app->cache->get($cacheKey));
                } else {
                    $status = 'data did not exist in cache';
                    $this->mapQueryForAjax($idCategory);
                    $result = Json::encode($this->listAddress);
                }

                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'result' => $status,
                    'listAddress' => $result,
                    'nameCache' => $cacheKey,
                ];
            }
        }
    }

    /**
     * @param $pid
     * @return $this
     */
    private function setIndexBreadcrumbs($pid = null)
    {
        $action = Yii::$app->controller->action->id;
        if (isset(Yii::$app->request->city)){
            $breadcrumbs[] = ['label' => Yii::t('business', 'Business_guide', ['city' => Yii::$app->request->city->title_ge]), 'url' => Url::to(['business/index'])];
        } else {
            $breadcrumbs[] = ['label' => Yii::t('business', 'Business'), 'url' => Url::to(['business/index'])];
        }
        switch ($action) {
            case 'index':
                if ($pid and $this->category) {
                    foreach ($this->category->parents()->all() as $item) {
                        $breadcrumbs[] = ['label' => $item->title, 'url' => Url::to(['business/index', 'pid' => $item->url])];
                    }
                    $breadcrumbs[] = ['label' => $this->category->title];
                }
                break;
            case 'top':
                $breadcrumbs[] = ['label' => 'Top 100'];
                break;
        }
        $this->breadcrumbs = $breadcrumbs;
        return $this;
    }

    /**
     * @param $pid
     * @return $this
     */
    private function setMapBreadcrumbs($pid = null)
    {
        $breadcrumbs[] = ['label' => Yii::t('business', 'Business_map'), 'url' => Url::to(['business/map'])];
        if ($pid && $this->category) {
            foreach ($this->category->parents()->all() as $item) {
                $breadcrumbs[] = ['label' => $item->title, 'url' => Url::to(['business/map', 'pid' => $item->url])];
            }
            $breadcrumbs[] = ['label' => $this->category->title];
        }
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @return $this
     */
    private function setIndexSeo()
    {
        $view = $this->view;
        $request = Yii::$app->request;
        $page = $request->get('page');
        $seo = $this->getSeo($this->category, false, $page);
        $city = Yii::$app->request->city;

        if (isset($seo['title'])) {
            $title = SeoHelper::registerTitle($view, SeoHelper::addPageCount($page, ArrayHelper::getValue($seo, 'title', '')));
        } else {
            $title = SeoHelper::registerTitle($view, Yii::t('business', 'Business index page title {city}', ['city' => $city->title]));
        }

        $meta = ['title' => $title];
        if (Yii::$app->request->get('page') === null || isset(Yii::$app->request->city->subdomain) && Yii::$app->request->city->subdomain == 'dnepr') {
            $meta['description'] = SeoHelper::addPageCount($page, ArrayHelper::getValue($seo, 'description', ''));
            if (isset($seo['keywords'])) {
                $meta['keywords'] = $seo['keywords'];
            } else {
                $meta['keywords'] = Yii::t('business', 'Business index page title {city}', ['city' => $city->title]);
            }
        } elseif (isset($seo['robots'])) {
            $meta['robots'] = $seo['robots'];
        }
        if (($request->get('sort') || $request->get('attr'))&& isset($seo['robots'])) {
            $meta['robots'] = $seo['robots'];
        }
        SeoHelper::registerAllMeta($view, $meta);

        SeoHelper::registerOgImage();

        return $this;
    }

    /**
     * @param null|BusinessCategory $cat
     * @return $this
     */
    private function setMapSeo($cat = null)
    {
        $view = $this->view;
        $city = Yii::$app->request->city;
        $domain = Yii::$app->params['appFrontend'];

        $url = empty($city->subdomain) ? $domain : "{$city->subdomain}.$domain";
        $lang_config = ['city_ge' => $city->title_ge, 'city' => $city->title, 'url' => $url];

        if (empty($cat)) {
            $view->title = Yii::t('business', 'Map_in_{city_ge}_{city}', $lang_config);
            $title = Yii::t('business', 'Map_title_in_{city_ge}_{url}', $lang_config);
            $desc = Yii::t('business', 'Map_desc_in_{city_ge}_{city}_{url}', $lang_config);
            $key = Yii::t('business', 'Map_key_in_{city}', $lang_config);
        } else {
            $lang_config['category'] = $cat->title;

            $view->title = Yii::t('business', 'Map_{city_ge}_{category}', $lang_config);
            $title = Yii::t('business', 'Map_name_{city_ge}_{category}_{url}', $lang_config);
            $desc = $cat->seo_description ? $cat->seo_description : Yii::t('business', 'Map_desc_in_{city_ge}_{city}_{url}', $lang_config);
            $desc = "$desc " . Yii::t('business', 'Map_desc_{city_ge}_on_map', $lang_config);
            $key = Yii::t('business', 'Map_key_{city}_{category}', $lang_config) . ($cat->seo_keywords ? ", {$cat->seo_keywords}" : '');
        }

        SeoHelper::registerAllMeta($view, ['title' => $title, 'description' => $desc, 'keywords' => $key]);
        SeoHelper::registerOgImage();

        return $this;
    }

    public function actionGetAllProductCategory(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $idCategory = $_POST['depdrop_parents'][0];
            if (!empty($idCategory)) {
                $rootcategory = ProductCategory::findOne(['id'=>(int)$idCategory]);
                if($rootcategory){
                    $idCategory = (!$rootcategory->isRoot()) ? $rootcategory->parents()->one()->id : $idCategory;
                    $listProdCat = ProductCompany::find()
                        ->leftJoin('ProductCategoryCategory','"ProductCategoryCategory"."ProductCompany" = product_company.id')
                        ->where(['ProductCategoryCategory.ProductCategory' => (int)$idCategory])
                        ->select(['"product_company"."id"', '"product_company"."title"'])
                        ->all();
                } else {
                    $listProdCat = ProductCompany::find()
                        ->leftJoin('ProductCategoryCategory','"ProductCategoryCategory"."ProductCompany" = product_company.id')
                        ->select(['"product_company"."id"', '"product_company"."title"'])
                        ->limit(100)
                        ->all();
                }

                foreach ($listProdCat as $value) {
                    $out[] = ['id' => $value->id, 'name' => $value->title];
                }
            }
        } else {

        }
        $selected = '';

        return Json::encode(['output' => $out, 'selected' => (string)$selected]);
    }

    /**
     * Получаем список категорий продуктов по ид предприятия
     * либо все если ид не установлен
     *
     */
    public function actionBusinessCategoryByBusiness()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parent = $_POST['depdrop_parents'][0];
            if (!empty($parent)) {
                #Предприятие по id
                $business = Business::find()->where(['id' => $parent])->one();

                $business_product_category = BusinessProductCategory::find()
                    ->select('*')
                    ->joinWith('product_category', 'product_category.id = product_category_id')
                    ->where(['business_category_id' => $business->idCategories])
                    ->all();

                #Преобразуем к виду [['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],[...],...]
                foreach ($business_product_category as $value) {
                    $product_categorys = ProductCategory::find()->where(['id' => $value->product_category->id])->orderBy('title ASC')->batch(100);
                    foreach ($product_categorys as $b) {
                        foreach ($b as $item) {
                            $out[] = ['id' => $item->id, 'name' => $item->title];
                            foreach ($item->children()->addOrderBy('title')->all() as $ch) {
                                $pref = ' ';
                                for ($i = 1; $i <= $ch->depth; $i++) {
                                    $pref .= '-';
                                }
                                $out[] = ['id' => $ch->id, 'name' => $pref . $ch->title];
                            }
                        }
                    }

                    $selected = $out[0]['id'];
                }

                if (!$business_product_category){
                    $selected = '';
                    $out[] = null;
                }
            }
        } else {
            foreach (ProductCategory::find()->roots()->orderBy('title ASC')->batch(100) as $b) {
                foreach ($b as $item) {
                    $out[] = ['id' => $item->id, 'name' => $item->title];
                    foreach ($item->children()->addOrderBy('title')->all() as $ch) {
                        $pref = ' ';
                        for ($i = 1; $i <= $ch->depth; $i++) {
                            $pref .= '-';
                        }
                        $out[] = ['id' => $ch->id, 'name' => $pref . $ch->title];
                    }
                }
            }
            $selected = '';
        }
        echo Json::encode(['output' => $out, 'selected' => (string)$selected]);
    }

    public function actionProductCategoryByBusiness()
    {
        $out = [];
        $selected = '';
        if (isset($_POST['depdrop_parents'])) {
            $parent = $_POST['depdrop_parents'][0];
            if (!empty($parent)) {
                #Предприятие по id
                $business = Business::find()->where(['id' => $parent])->one();

                $business_product_category = BusinessProductCategory::find()
                    ->select('*')
                    ->joinWith('product_category', 'product_category.id = product_category_id')
                    ->where(['business_category_id' => $business->idCategories])
                    ->all();

                if ($business_product_category){
                    #Преобразуем к виду [['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],[...],...]
                    foreach ($business_product_category as $value) {
                        $product_categorys = ProductCategory::find()->where(['id' => $value->product_category->id])->orderBy('title ASC')->batch(100);
                        foreach ($product_categorys as $b) {
                            foreach ($b as $item) {
                                $out[] = ['id' => $item->id, 'name' => $item->title];
                                foreach ($item->children()->addOrderBy('title')->all() as $ch) {
                                    $pref = ' ';
                                    for ($i = 1; $i <= $ch->depth; $i++) {
                                        $pref .= '-';
                                    }
                                    $out[] = ['id' => $ch->id, 'name' => $pref . $ch->title];
                                }
                            }
                        }

                        $selected = $out[0]['id'];
                    }
                } else {
                    foreach (ProductCategory::find()->roots()->orderBy('title ASC')->batch(100) as $b) {
                        foreach ($b as $item) {
                            $out[] = ['id' => $item->id, 'name' => $item->title];
                            foreach ($item->children()->addOrderBy('title')->all() as $ch) {
                                $pref = ' ';
                                for ($i = 1; $i <= $ch->depth; $i++) {
                                    $pref .= '-';
                                }
                                $out[] = ['id' => $ch->id, 'name' => $pref . $ch->title];
                            }
                        }
                    }
                    $selected = '';
                }
            }
        } else {
            foreach (ProductCategory::find()->roots()->orderBy('title ASC')->batch(100) as $b) {
                foreach ($b as $item) {
                    $out[] = ['id' => $item->id, 'name' => $item->title];
                    foreach ($item->children()->addOrderBy('title')->all() as $ch) {
                        $pref = ' ';
                        for ($i = 1; $i <= $ch->depth; $i++) {
                            $pref .= '-';
                        }
                        $out[] = ['id' => $ch->id, 'name' => $pref . $ch->title];
                    }
                }
            }
        }
        echo Json::encode(['output' => $out, 'selected' => (string)$selected]);
    }

    public function actionCheckCompanys()
    {
        $arr = [];
        $id = $_POST['id'];

        $id = trim($id, ',');

        $ids = explode(',', $id);

        foreach ($ids as $item){
            if (is_numeric($item)) {
                $model = Business::findOne((int)$item);
            } else {
                $model = null;
            }

            if($model){
                $arr[] = [
                    'id' => $model->id,
                    'class' => '',
                    'title' => $model->title,
                    'cityTitle' => $model->city->title,
                ];
            }
            else{
                $arr[] = [
                    'id' => (int)$item,
                    'class' => 'id-error',
                    'title' => 'Не найденно',
                    'cityTitle' => '',
                ];
            }
        }

        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $business Business
     */
    public function setSearchBreadcrumbs($business){
        $alias = "{$business->id}-{$business->url}";
        $this->breadcrumbs = [[
            'label' => $business->title,
            'url' => Url::to(['/business/view', 'alias' => $alias])
        ]
        ];

        $url = Url::to(['/business/goods', 'alias' => $alias, 'urlCategory' => 'goods']);
        $this->breadcrumbs[] = ['label' => 'Товары', 'url' => $url];
        $this->breadcrumbs[] = ['label' => 'Поиск'];
    }

    public function actionAbout($alias){
        $url = explode('-', $alias, 2);
        $url[0] = (int)$url[0];

        if (!$url[0]) {
            throw new HttpException(404);
        }
        $this->businessModel = Business::find()->where(['id' => (int)$url[0]])->one();
        if (!$this->businessModel){
            throw new HttpException(404);
        }

        $this->initTemplate();

        $alias = "{$this->businessModel->id}-{$this->businessModel->url}";
        $this->breadcrumbs = [[
            'label' => $this->businessModel->title,
            'url' => Url::to(['/business/view', 'alias' => $alias])
        ]
        ];

        $this->breadcrumbs[] = ['label' => 'О нас'];

        return $this->render('about', [
            'model' => $this->businessModel
        ]);
    }

    public function actionBusinessContact($alias){
        $url = explode('-', $alias, 2);
        $url[0] = (int)$url[0];

        if (!$url[0]) {
            throw new HttpException(404);
        }
        $this->businessModel = Business::find()->where(['id' => (int)$url[0]])->one();
        if (!$this->businessModel){
            throw new HttpException(404);
        }

        $this->initTemplate();

        $alias = "{$this->businessModel->id}-{$this->businessModel->url}";
        $this->breadcrumbs = [[
            'label' => $this->businessModel->title,
            'url' => Url::to(['/business/view', 'alias' => $alias])
        ]
        ];

        $this->breadcrumbs[] = ['label' => 'О нас'];

        return $this->render('contact', [
            'model' => $this->businessModel
        ]);
    }
}
