<?php

namespace frontend\controllers;

use common\helpers\SearchHelper;
use common\models\Action;
use common\models\ActionCategory;
use common\models\Business;
use common\models\BusinessAddress;
use common\models\BusinessCategory;
use common\models\ViewCount;
use frontend\extensions\BusinessFormatTime\BusinessFormatTime;
use frontend\helpers\SeoHelper;
use Yii;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

class SearchController extends Controller
{
    public $breadcrumbs;
    
    public function actionIndex($s = null, $id_city = null, $type = null, $page = null)
    {
        if (!$s && $s != '') {
            return $this->redirect(Yii::$app->request->referrer);
        }

        $types = ['Action', 'Afisha', 'Post', 'Business', 'WorkVacantion', 'Ads', 'Business'];
        if ($type != null && isset($types[$type])) {
            $name = $types[$type];
        } else {
            $name = 'Business';
            $type = array_search($name, $types);
        }

        /** @var ActiveRecord $m */
        $m = "\\common\\models\\{$name}";
        $listAddress = [];
        $s = $s ? $s : '';

        switch ($type) {
            case(0)://'Action'
                $query = $m::find()
                    ->where(['ilike', 'action.title', $s])
                    ->orWhere(['ilike', 'action.description', $s])
                    ->leftJoin('business', 'action."idCompany" = business.id');
                if ($id_city) {
                    $query->andWhere(['business."idCity"' => $id_city]);
                }
                $name = Yii::t('action', 'Promotions');
                break;
            case(1)://'Afisha'
                $query = $m::find()
                    ->where(['ilike', '"afisha"."title"', $s])
                    ->orWhere(['ilike', '"afisha"."description"', $s]);

                if ($id_city && $id_city != '' && $id_city != 0){
                    $cities = array($id_city);
                    $query = $m::find()
                        ->leftJoin(
                        'business',
                        'business.id = ANY(afisha."idsCompany") AND business."idCity" = ANY(:city) AND afisha."isFilm" = 0',
                        ['city' => $this->php_to_postgres_array($cities)]
                    )
                        ->leftJoin(
                            'schedule_kino',
                            'afisha.id = schedule_kino."idAfisha" AND schedule_kino."idCity" = ANY(:city) AND afisha."isFilm" = 1',
                            ['city' =>  $this->php_to_postgres_array($cities)]
                        )
                        ->where(['or',
                            ['business."idCity"' => $cities],
                            ['schedule_kino."idCity"' => $cities],
                        ])
                        ->where(['ilike', '"afisha"."title"', $s])
                        ->orWhere(['ilike', '"afisha"."description"', $s]);
                }
                $name = Yii::t('afisha', 'Poster');
                break;
            case(2)://'Post'
                $query = $m::find()
                    ->where(['ilike', 'title', $s])
                    ->orWhere(['ilike', 'fullText', $s]);
                if ($id_city) {
                    $query->andWhere(['idCity' => $id_city]);
                }
                $name = Yii::t('post', 'Posts');
                break;
            case(4)://'WorkVacantion'
                $query = $m::find()
                    ->where(['ilike', '"title"', $s])
                    ->orWhere(['ilike', '"description"', $s]);
                if ($id_city) {
                    $query->andWhere(['idCity' => $id_city]);
                }
                $name = Yii::t('vacantion', 'Vacantions');
                break;
            case(5)://'Ads'
                $query = $m::find()
                    ->andFilterWhere(['like', 'title', $s])
                    ->orFilterWhere(['like', 'description', $s]);
                if ($id_city) {
                    $query->orFilterWhere(['idCity' => $id_city]);
                }
                $name = Yii::t('ads', 'Ads');
                break;
            default://'Business'
                $query = $m::find()
                    ->where(['ilike', 'title', $s])
                    ->orWhere(['ilike', 'description', $s]);
                if ($id_city) {
                    $query->andWhere(['idCity' => $id_city]);
                }
                /** @var Business $item */
                $models = $query->limit(100)->all();
                foreach ($models as $item) {
                    $bft = BusinessFormatTime::widget(['id' => $item->id, 'format' => false]);
                    $business_working_time = $this->fixForMap($bft);
                    /** @var BusinessAddress $address */
                    foreach ($item->address as $address) {
                        $working_time = empty($address->working_time) ? $business_working_time : $this->fixForMap($address->working_time);
                        $listAddress[] = [
                            'address' => $this->fixForMap($address->address),
                            'phone' => $this->fixForMap($address->phone),
                            'working_time' => $working_time,
                            'lat' => $this->fixForMap($address->lat),
                            'lon' => $this->fixForMap($address->lon),
                            'idBusiness' => $this->fixForMap($address->idBusiness),
                            'title' => $this->fixForMap($item->title),
                            'image' => $item->image ? $this->fixForMap(Yii::$app->files->getUrl($item, 'image', 165)) : false,
                            'link' => $this->fixForMap(Html::a('Подробнее', Yii::$app->urlManager->createUrl(['business/view', 'alias' => "{$item->id}-{$item->url}"]))),
                        ];
                    }
                }
                $name = Yii::t('business', 'Business');
                break;
        }

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $modelResult = $query->offset($pages->offset)->limit($pages->limit)->all();

        //это для сфинкс поиска
//        $model = [];
//        $modelResult = [];
//        $pages = null;
//
//        //if ($s) {
//        if ($s) {
//            Yii::$app->search->text($s);
//        }
//        if ($type) {
//            Yii::$app->search->type($type);
//        }
//        if ($page) {
//            Yii::$app->search->page($page);
//        }
//        if ($id_city) {
//            Yii::$app->search->cityId($id_city);
//        }
//        $model = Yii::$app->search->all();var_dump($model);die;
//
//        $pages = new Pagination(['totalCount' => $model['total_found']]);
//        //}
//
//        if ($model && !empty($model['matches'])) {
//
//            foreach ($model['matches'] as $key => $value) {
//                switch ($value['attrs']['model_name']) {
//                    case(1): $name = 'Business'; break;
//                    case(2): $name = 'Action'; break;
//                    case(3): $name = 'Afisha'; break;
//                    case(4): $name = 'WorkVacantion'; break;
//                    case(5): $name = 'WorkResume'; break;
//                    case(6): $name = 'Ads'; break;
//                }
//
//                $m = '\common\models\\' . $name;
//                $modelResult[] = $m::findOne($key);
//
//            }
//        }

        return $this->render('sphinx', [
            'modelResult' => $modelResult,
            'search' => $s,
            'id_city' => $id_city,
            'type' => $type,
            'searchName' => $name,
            'page' => ($page)? $page : 0,
            'pages' => $pages,
            'listAddress' => $listAddress,
        ]);
    }

    public function php_to_postgres_array($phpArray)
    {
        return '{' . join(',', $phpArray) . '}';
    }

    public function actionAds($s = null, $pid = null){
        $get = Yii::$app->request->get();
        $this->redirect('/ads/index');
    }

    public function actionBusiness($s = null, $pid = null)
    {
        Yii::$app->session->set('viewBusiness', 'index');
        if (empty(Yii::$app->params['SUBDOMAINID'])) {
            //throw new HttpException(404);
        }

        if (!$s && $s != '') {
            return $this->redirect(Url::to(['/business/index', 'pid' => $pid]));
        }
        $get = Yii::$app->request->get();
        if (isset($get['page']) and (int)$get['page'] === 1) {
            $get['page'] = null;
            Yii::$app->response->redirect(array_merge(['search/business'], $get), 301);
        }
        $s = SearchHelper::modernSearchString($s);

        $query = Business::find()
            ->leftJoin('business_category', 'business_category.id = ANY(business."idCategories")')
            ->where(SearchHelper::getBusinessOrWhere($s))
            ->andWhere(['business_category.sitemap_en' => 1]);

        if (!empty(Yii::$app->params['SUBDOMAINID'])) {
            $query = $query->andWhere(['idCity' => Yii::$app->params['SUBDOMAINID']]);
        }

        $category = null;
        if ($pid) {
            /** @var BusinessCategory $category */
            $category = BusinessCategory::find()->where(['url' => $pid, 'sitemap_en' => 1])->one();
            if ($category) {
                define('CATEGORYID', $category->id, true);

                $category_children = ArrayHelper::getColumn($category->children()->all(), 'id');
                $category_children[] = $category->id;

                $cl = '{' . implode(',', $category_children) . '}';
                $query->andWhere(['&&', 'idCategories', $cl]);
            }
        }

        $sort = Yii::$app->request->get('sort');
        switch ($sort) {
            case Business::SORT_VIEWS_ASC:
                $query->leftJoin('view_count', 'view_count.item_id = business.id AND view_count.category = :cat', ['cat' => ViewCount::CAT_BUSINESS])
                    ->groupBy(['business.id'])->orderBy([
                        'COALESCE(SUM(view_count.value), 0)' => SORT_ASC,
                        'business.ratio' => SORT_DESC,
                        'business.id' => SORT_DESC,
                    ]);
                SeoHelper::registerMetaTag($this->view, 'robots', 'noindex, nofollow');
                break;
            case Business::SORT_VIEWS_DESC:
                $query->leftJoin('view_count', 'view_count.item_id = business.id AND view_count.category = :cat', ['cat' => ViewCount::CAT_BUSINESS])
                    ->groupBy(['business.id'])->orderBy([
                        'COALESCE(SUM(view_count.value), 0)' => SORT_DESC,
                        'business.ratio' => SORT_DESC,
                        'business.id' => SORT_DESC,
                    ]);
                SeoHelper::registerMetaTag($this->view, 'robots', 'noindex, nofollow');
                break;
            case Business::SORT_RATING_ASC:
                $query->groupBy(['business.ratio', 'business.id'])->orderBy([
                    'COALESCE((business.total_rating / NULLIF(business.quantity_rating, 0)), 0)' => SORT_ASC,
                    'COALESCE((business.total_rating % NULLIF(business.quantity_rating, 0)), 0)' => SORT_ASC,
                    'business.ratio' => SORT_DESC,
                    'business.id' => SORT_DESC
                ]);
                SeoHelper::registerMetaTag($this->view, 'robots', 'noindex, nofollow');
                break;
            case Business::SORT_RATING_DESC:
                $query->groupBy(['business.ratio', 'business.id'])->orderBy([
                    'COALESCE((business.total_rating / NULLIF(business.quantity_rating, 0)), 0)' => SORT_DESC,
                    'COALESCE((business.total_rating % NULLIF(business.quantity_rating, 0)), 0)' => SORT_DESC,
                    'business.ratio' => SORT_DESC,
                    'business.id' => SORT_DESC,
                ]);
                SeoHelper::registerMetaTag($this->view, 'robots', 'noindex, nofollow');
                break;
            default:
                $this->setBusinessMeta($category);
                $query->groupBy(['business.ratio', 'business.id'])->orderBy(['business.ratio' => SORT_DESC, 'business.id' => SORT_DESC]);
        }

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);

        $query->offset($pages->offset)->limit($pages->limit);
        $models = $query->all();

        $pages->pageSizeParam = false;
        $this->breadcrumbs[] = ['label' => Yii::t('business', 'Business'), 'url' => Url::to(['business/index'])];
        $s = trim(str_replace(['_', '  ', '   '], ' ', $s));

        $listAddress = [];
        /** @var Business $item */
        foreach ($models as $item) {
            $business_working_time = $this->fixForMap(BusinessFormatTime::widget(['id' => $item->id, 'format' => false]));
            /** @var BusinessAddress $address */
            foreach ($item->address as $address) {
                $working_time = empty($address->working_time) ? $business_working_time : $this->fixForMap($address->working_time);
                $listAddress[] = [
                    'address' => $this->fixForMap($address->address),
                    'phone' => $this->fixForMap($address->phone),
                    'working_time' => $working_time,
                    'lat' => $this->fixForMap($address->lat),
                    'lon' => $this->fixForMap($address->lon),
                    'idBusiness' => $this->fixForMap($address->idBusiness),
                    'title' => $this->fixForMap($item->title),
                    'image' => $item->image ? $this->fixForMap(Yii::$app->files->getUrl($item, 'image', 165)) : false,
                    'link' => $this->fixForMap(Html::a('Подробнее', Yii::$app->urlManager->createUrl(['business/view', 'alias' => "{$item->id}-{$item->url}"]))),
                ];
            }
        }

        return $this->render('business', [
            'models' => $models,
            'pages' => $pages,
            'pid' => $pid,
            's' => $s,
            'listAddress' => $listAddress,
        ]);
    }

    public function actionAction($s = null, $pid = null)
    {
        if (!$s) {
            return $this->redirect(Url::to(['/business/index', 'pid' => $pid]));
        }
        $get = Yii::$app->request->get();
        if (isset($get['page']) and (int)$get['page'] === 1) {
            $get['page'] = null;
            Yii::$app->response->redirect(array_merge(['search/action'], $get), 301);
        }
        $query = Action::find();
        if (!empty(Yii::$app->params['SUBDOMAINID'])) {
            $query->leftJoin('business', 'action."idCompany" = business.id')
                ->andWhere(['business."idCity"' => Yii::$app->params['SUBDOMAINID']]);
        }
        $query->andWhere(SearchHelper::getActionOrWhere($s));

        if ($pid) {
            /** @var ActionCategory $category */
            $category = ActionCategory::find()->where(['url' => $pid])->one();
            if ($category) {
                define('CATEGORYID', $category->id, true);
                $query->andWhere(['idCategory' => $category->id]);
            }
        }

        $now = (new \DateTime())->setTimezone(new \DateTimeZone(Yii::$app->params['timezone']));
        $now->setTimestamp(time());
        $dateTwo = $now->modify('+1 day');

        $query->andWhere(['>=', 'dateEnd', $now->format('Y-m-d 00:00:00')])
            ->andWhere(['<=', 'dateStart', $dateTwo->format('Y-m-d 00:00:00')]);

        $countQuery = clone $query;
        $countQuery = $countQuery->count();
        $pages = new Pagination(['totalCount' => $countQuery, 'pageSize' => 10]);

        $query->orderBy('dateStart ASC')->offset($pages->offset)->limit($pages->limit);
        $models = $query->all();

        $pages->pageSizeParam = false;

        SeoHelper::registerTitle($this->view, "Поиск акций $s - CityLife");
        
        return $this->render('action', [
            'models' => $models,
            'pages' => $pages,
            'pid' => $pid,
            's' => $s,
        ]);
    }

    public function actionMap($s = null, $pid = null)
    {
        Yii::$app->session->set('viewBusiness', 'map');
        if (empty(Yii::$app->request->city)) {
            throw new HttpException(404);
        }
        if (!$s) {
            return $this->redirect(Url::to(['/business/index', 'pid' => $pid]));
        }
        $s = SearchHelper::modernSearchString($s);
        $category = null;
        if ($pid) {
            /** @var BusinessCategory $category */
            $category = BusinessCategory::find()->where(['url' => $pid])->one();
            if ($category) {
                define('CATEGORYID', $category->id, true);

                $category_children = ArrayHelper::getColumn($category->children()->all(), 'id');
                $category_children[] = $category->id;

                $cl = '{' . implode(',', $category_children) . '}';
            }
        }

        $query = Business::find()
            ->select(['business.id', 'business.title', 'business.url'])
            ->with('address')
            ->where(['idCity' => Yii::$app->params['SUBDOMAINID']])
            ->andWhere(SearchHelper::getBusinessOrWhere($s))
            ->limit(100);

        if (!empty($cl)) {
            $query->andWhere(['&&', 'idCategories', $cl]);
        }
        $listAddress = $this->setListAddress($query);
        
        $s = trim(str_replace(['_', '  ', '   '], ' ', $s));

        $this->breadcrumbs[] = ['label' => Yii::t('business', 'Business'), 'url' => Url::to(['business/index'])];

        $this->setMapMeta($category);

        return $this->render('map', [
            'listAddress' => $listAddress,
            's' => $s,
            'pid' => $pid,
            'titleCategory' => $category ? $category->title : null,
        ]);
    }
    
    private function setListAddress(ActiveQuery $query)
    {
        $result = [];
        foreach ($query->batch() as $b) {
            /** @var Business $item */
            foreach ($b as $item) {
                $business_working_time = $this->fixForMap(BusinessFormatTime::widget(['id' => $item->id, 'format' => false]));
                /** @var BusinessAddress $address */
                foreach ($item->address as $address) {
                    $working_time = empty($address->working_time) ?
                        $business_working_time : $this->fixForMap($address->working_time);
                    $result[] = [
                        'address' => $this->fixForMap($address->address),
                        'phone' => $this->fixForMap($address->phone),
                        'working_time' => $working_time,
                        'lat' => $this->fixForMap($address->lat),
                        'lon' => $this->fixForMap($address->lon),
                        'idBusiness' => $this->fixForMap($address->idBusiness),
                        'title' => $this->fixForMap($item->title),
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
        return $result;
    }
    
    private function fixForMap($str)
    {
        return str_replace(['\\', '"', "'",  "\t", chr(9)], ['', '\"', '\"', '', ''], $str);
    }

    /**
     * @param null|BusinessCategory $cat
     */
    private function setMapMeta($cat = null)
    {
        $view = $this->view;
        $city = Yii::$app->request->city;
        $domain = Yii::$app->params['appFrontend'];

        $lang_config = ['city_ge' => $city->title_ge, 'city' => $city->title, 'url' => "$city->subdomain.$domain"];

        if (empty($cat)) {
            $view->title = Yii::t('business', 'Map_in_{city_ge}_{city}', $lang_config);
            $title = Yii::t('business', 'Map_title_in_{city_ge}_{url}', $lang_config);
            $desc = Yii::t('business', 'Map_desc_in_{city_ge}_{city}_{url}', $lang_config);
            $key = Yii::t('business', 'Map_key_in_{city}', $lang_config);
        } else {
            $lang_config['category'] = $cat->title;

            $view->title = Yii::t('business', 'Map_{city_ge}_{category}', $lang_config);
            $title = Yii::t('business', 'Map_name_{city_ge}_{category}_{url}', $lang_config);
            $desc = $cat->seo_description ?  $cat->seo_description : Yii::t('business', 'Map_desc_in_{city_ge}_{city}_{url}', $lang_config);
            $desc = "$desc " . Yii::t('business', 'Map_desc_{city_ge}_on_map', $lang_config);
            $key = Yii::t('business', 'Map_key_{city}_{category}', $lang_config) . ($cat->seo_keywords ? ", {$cat->seo_keywords}" : '');
        }

        SeoHelper::registerAllMeta($view, ['title' => $title, 'description' => $desc, 'keywords' => $key]);
    }

    private function setBusinessMeta($cat)
    {
        $view = $this->view;
        $page = (int)Yii::$app->request->get('page', 1);
        $seo = $this->getSeo($cat);

        $title = SeoHelper::registerTitle($view, SeoHelper::addPageCount($page, ArrayHelper::getValue($seo, 'title', '')) . ' - CityLife');

        $meta = ['title' => $title];
        if ($page === 1) {
            $meta['description'] = SeoHelper::addPageCount($page, ArrayHelper::getValue($seo, 'description', ''));
            $meta['keywords'] = ArrayHelper::getValue($seo, 'keywords');
        } elseif (isset($seo['robots'])) {
            $meta['robots'] = ArrayHelper::getValue($seo, 'robots');
        }
        SeoHelper::registerAllMeta($view, $meta);
    }

    private function getSeo($model)
    {
        $page = Yii::$app->request->get('page');
        $arr = [];

        if (!$model) {
            $arr['title'] = Yii::t('business', 'seo_title', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            if ($page === null) {
                $arr['keywords'] = Yii::t('business', 'seo_keywords', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
                $arr['description'] = Yii::t('business', 'seo_description', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            } else $arr['robots'] = 'noindex, nofollow';
        } else {
            $arr['title'] = $model->seo_title ? $model->seo_title : Yii::t('business', 'seo_title', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            if ($page === null) {
                $arr['description'] = $model->seo_description ? $model->seo_description : Yii::t('business', 'seo_description', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
                $arr['keywords'] = $model->seo_keywords ? $model->seo_keywords : Yii::t('business', 'seo_keywords', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            } else {
                $arr['robots'] = 'noindex, nofollow';
            }
        }

        return $arr;
    }
}
