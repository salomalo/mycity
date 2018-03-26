<?php

namespace frontend\controllers;

use common\models\Log;
use DateTime;
use yii;
use yii\web\HttpException;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Response;
use common\helpers\SearchHelper;
use common\models\search\Action as ActionSearch;
use common\models\Action;
use common\models\ActionCategory;
use common\models\Business;
use common\models\File;
use common\models\Tag;
use common\models\User;
use frontend\behaviors\ViewBehavior;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use frontend\helpers\SeoHelper;


/**
 * Description of ActionController
 *
 * @author dima
 */
class ActionController extends Controller
{
    public $alias_category = '';
    public $breadcrumbs = [];
    public $listCityBusiness = [];
    public $sotrTime = false;
    public $sotrNew = false;
    public $archive = false;
    public $timeZone;
    public $calendarDate;
    public $date;
    public $time;
    public $photo;

    public function init()
    {
        parent::init();

        $this->breadcrumbs = [
            ['label' => Yii::t('action', 'Promotions')],
        ];
    }
    public function __construct($id, $module, $config = array())
    {

        $this->timeZone = new \DateTimeZone(Yii::$app->params['timezone']);
        parent::__construct($id, $module, $config);
    }

    public function actionIndex($pid = null, $time = null, $sort = null, $archive = false, $msg = false)
    {
        if ($url = SeoHelper::redirectFromFirstPage()) {
            return $this->redirect($url, 301);
        }

        $archive = (bool)$archive;

        $query = ActionSearch::find()
            ->joinWith('comment')
            ->select(['action.*', 'comment_count' => 'COUNT(comment.id)'])
            ->with(['companyName', 'category', 'countView']);

        if ($city = Yii::$app->request->city) {
            $query->joinWith('companyName')->andWhere(['business."idCity"' => $city->id]);
        }

        $cat = null;
        if ($pid) {
            if (!($cat = ActionCategory::findOne(['url' => $pid]))) {
                throw new HttpException(404);
            }
            $pid = $cat->id;
            define('CATEGORYID', $pid, true);
            $query->joinWith('category')->andWhere(['idCategory' => $pid]);
        }

        $this->setIndexBreadcrumbs($archive, $pid, $cat);

        $now = new DateTime();
        $now->setTimezone($this->timeZone);
        $date = time();
        if ($time and $date = strtotime($time)) {
            $time = date('Y-m-d', $date);
        }
        $now->setTimestamp($date);
        $this->calendarDate = $now->format('d-m-Y');

        $nowDate = $now->format('Y-m-d 00:00:00');
        $dateTwo = $now->modify('+1 day')->format('Y-m-d 00:00:00');

        if (!$archive) {
            $query->andWhere(['>=', 'dateEnd', $nowDate])->andWhere(['<=', 'dateStart', $dateTwo])
                ->groupBy('action."dateStart"')->orderBy(['action."dateStart"' => SORT_ASC]);
        } else {
            $this->archive = true;
            $query->andWhere(['<', 'dateEnd', $nowDate])
                ->groupBy(['action."dateEnd"', 'action.id'])->orderBy(['action."dateEnd"' => SORT_DESC]);
        }
        if ($sort === 'time') {
            $title = ($cat and $cat->seo_title) ? $cat->seo_title :
                ($city ? Yii::t('action', 'Action index page title {city}', ['city' => $city->title]) :
                    Yii::t('action', 'Action index page title Ukraine'));
            SeoHelper::registerAllMeta($this->view, ['robots' => 'noindex, nofollow', 'title' => SeoHelper::registerTitle($this->view, $title)]);
            SeoHelper::registerOgImage();

            $this->sotrTime = true;
            $query->groupBy(['action."dateEnd"', 'action.id'])->orderBy(['action."dateEnd"' => SORT_ASC]);
        } elseif ($sort === '-expiration_date') {
            $this->setIndexSeo($cat);
            $query->addSelect(['(select "dateEnd" - now()) AS diff_date']);
            $query->groupBy('action.id')->orderBy(['diff_date' => SORT_ASC]);
        } else if ($sort === 'expiration_date') {
            $this->setIndexSeo($cat);
            $query->addSelect(['(select "dateEnd" - now()) AS diff_date']);
            $query->groupBy('action.id')->orderBy(['diff_date' => SORT_DESC]);
        } elseif (!$sort and !$archive) {
            $this->setIndexSeo($cat);
            $this->sotrNew = true;
            $query->groupBy('action.id')->orderBy(['action.id' => SORT_DESC]);
        } else {
            $query->groupBy('action.id');
            $title = ($cat and $cat->seo_title) ? $cat->seo_title :
                ($city ? Yii::t('action', 'Action index page title {city}', ['city' => $city->title]) :
                    Yii::t('action', 'Action index page title Ukraine'));
            SeoHelper::registerAllMeta($this->view, ['robots' => 'noindex, nofollow', 'title' => SeoHelper::registerTitle($this->view, $title)]);
            SeoHelper::registerOgImage();
        }

        if ($sort = Yii::$app->request->get('sort')) {
            $order = SORT_ASC;
            if (mb_substr($sort, 0, 1) === '-') {
                $order = SORT_DESC;
                $sort = mb_substr($sort, 1, mb_strlen($sort));
            }
            if ($sort !== 'views' && $sort !== 'expiration_date') {
                throw new HttpException(404);
            }

            if ($sort !== 'expiration_date') {
                $query->joinWith(['countView'])->addSelect(['countViews' => 'COALESCE(count_views.count, 0)'])
                    ->addGroupBy(['countViews'])->orderBy(['countViews' => $order]);
            }
        }

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
        $pages->pageSizeParam = false;
        $query->offset($pages->offset)->limit($pages->limit);

        return $this->render('index', [
            'models' => $query->all(),
            'pages' => $pages,
            'pid' => $pid,
            'time' => $time,
            'archive' => $this->archive,
            'titleCategory' => ($cat) ? $cat->title : null,
            'url' => ($cat) ? $cat->url : null,
            'showLoginMassage' => $msg,
        ]);
    }
    public function actionView($alias)
    {
        $url = explode('-', $alias);
        if (($url[0] > Yii::$app->params['maxIntValue']) or !(int)$url[0]) {
            throw new HttpException(404);
        }
        /** @var Action $model */
        $model = Action::find()->where(['id' => $url[0]])->with(['companyName', 'category'])->one();
        if (empty($model)) {
            throw new HttpException(404);
        }
        $check = $this->checkAlias($url[0], $model->url, $alias);
        if ($check !== true) {
            return $check;
        }

        define('CATEGORYID', $model->category->id, true);
        $view = $this->view;
        $this->setViewSeo($model);

        $this->breadcrumbs = [[
            'label' => Yii::t('action', 'Promotions'),
            'url' => Url::to(['action/index']),
        ]];

        if (strtotime($model->dateEnd) < time()) {
            $this->breadcrumbs[] = [
                'label' => Yii::t('action', 'Archive_shares'),
                'url' => Url::to(['action/index', 'archive' => 'archive'])
            ];
            $this->breadcrumbs[] = [
                'label' => $model->category->title,
                'url' => Url::to(['action/index', 'pid' => $model->category->url, 'archive' => 'archive']),
            ];
        } else {
            $this->breadcrumbs[] = [
                'label' => $model->category->title,
                'url' => Url::to(['action/index', 'pid' => $model->category->url])
            ];
        }

        $this->breadcrumbs[] = ['label' => $model->title];

        $model->attachBehavior('view', [
            'class' => ViewBehavior::className(),
            'type' => File::TYPE_ACTION,
            'id' => $model->id,
        ]);

        $this->date = date('Y-m-d');

        $view->params['breadcrumbs'][] = ['label' => Yii::t('action', 'Promotions'), 'url' => ['index']];
        $view->params['breadcrumbs'][] = $model->title;

        return $this->render('view', ['model' => $model]);
    }
    public function actionCreate()
    {
        if (empty(Yii::$app->user->identity)) {
            return $this->redirect(['/action/index', 'msg' => true]);
        }

        $this->breadcrumbs = [
            ['label' => Yii::t('action', 'Promotions'), 'url' => Url::to(['action/index'])],
            ['label' => Yii::t('action', 'Add_post')],
        ];

        $model = new Action();

        $titleCompany = '';
        $this->date = date('Y-m-d');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->idCompany) {
                $business = Business::find()->select('title')->where(['id' => $model->idCompany])->one();
                if ($business) {
                    $titleCompany = $business->title;
                }
            }

            $dateStart = new \DateTime($model->dateStart);
            $dateEnd = new \DateTime($model->dateEnd);

            $dateStart->setTime(00, 00, 00);
            $dateEnd->setTime(23, 59, 59);

            $model->dateStart = $dateStart->format('Y-m-d H:i:s');
            $model->dateEnd = $dateEnd->format('Y-m-d H:i:s');

            if ($model->save()) {
                $this->saveTegs($model->tags);
                Log::addUserLog("action[create]  ID: {$model->id}", $model->id, Log::TYPE_ACTION);
                return $this->redirect(['/action/index'],301);
            }
        }

        $this->view->title = Yii::t('action', 'Add_post') . ' - CityLife';

        return $this->render('create', [
            'model' => $model,
            'titleCompany' => $titleCompany,
        ]);

    }
    public function actionUpdate($alias)
    {
        $arr = explode('-', $alias);
        /** @var Action $model */
        $model = Action::find()->where(['id' => $arr[0], 'url' => str_replace($arr[0] . '-', '', $alias)])->one();

        if (!$model) {
            throw new HttpException(404);
        }

        if (Yii::$app->user->identity->role != User::ROLE_EDITOR) {
            if ($model->companyName->idUser != Yii::$app->user->identity->id) {
                throw new HttpException(404);
            }
        }

        $model->tags = explode(', ', $model->tags);

        $this->date = date('Y-m-d');

        $this->breadcrumbs = [
            ['label' => Yii::t('action', 'Promotions'), 'url' => Url::to(['action/index'])],
            ['label' => Yii::t('action', 'Edit_post')],
            ['label' => $model->title],
        ];

        if ($model->load(Yii::$app->request->post())) {
            $dateStart = new \DateTime($model->dateStart);
            $dateEnd = new \DateTime($model->dateEnd);

            $dateStart->setTime(00, 00, 00);
            $dateEnd->setTime(23, 59, 59);

            $model->dateStart = $dateStart->format('Y-m-d H:i:s');
            $model->dateEnd = $dateEnd->format('Y-m-d H:i:s');

            if ($model->save()) {
                $this->saveTegs($model->tags);
//                Log::addLog('action[update]', "ID: {$model->id}");
                Log::addUserLog("action[update]  ID: {$model->id}", $model->id, Log::TYPE_ACTION);

                return $this->redirect(Url::to($model->getRoute()),301);
            }
        }

        $model->companyTitle = $model->companyName->title;
        $model->actionCity = $model->companyName->city->title;

        $this->view->title = Yii::t('action', 'Edit_post') . '- CityLife';

        return $this->render('update', ['model' => $model]);
    }
    public function actionBusinessList($q = null, $id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $model = Business::find();
            $model->select(['id', 'title'])->limit(20)->where(['idUser' => Yii::$app->user->identity->id]);
            if (!empty(Yii::$app->params['SUBDOMAINID'])) {
                $model->where(['idCity' => Yii::$app->params['SUBDOMAINID']]);
            }
            $q = SearchHelper::modernSearchString($q);
            $model->andFilterWhere(SearchHelper::getBusinessOrWhere($q));
            $model = $model->all();

            $data = [];

            foreach ($model as $item) {
                $data[] = [
                    'id' => $item->id,
                    'text' => $item->title,
                ];
            }
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Business::find($id)->title];
        }
        return $out;
    }

    public function checkAlias($idUrl, $modelUrl, $alias)
    {
        $aliasFromUrl = str_replace($idUrl . '-', '', $alias);
        if ($modelUrl != $aliasFromUrl) {
            return $this->redirect($idUrl . '-' . $modelUrl, 301);
        }
        return true;
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
    private function getSeo($model, $isFull = false, $page = null)
    {
        $arr = [];

        $title = (Yii::$app->params['SUBDOMAINTITLE']) ? Yii::t('action', 'seo_title', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]) :
            Yii::t('action', 'seo_title_no_city');
        $description = (Yii::$app->params['SUBDOMAINTITLE']) ? Yii::t('action', 'seo_description', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]) :
            Yii::t('action', 'seo_description_no_city');
        if (!$model) {
            //action
            $arr['title'] = $title;
            if ($page === 1) {
                $arr['description'] = $description;
                $arr['keywords'] = Yii::t('action', 'seo_keywords', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            } else {
                $arr['robots'] = 'noindex, nofollow';
            }
        }

        if ($model && !$isFull) {
            //action/category
            $arr['title'] = ($model->seo_title) ?
                $this->checkSeoCity(str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_title)) : $title;
            if ($page === 1) {
                $arr['description'] = ($model->seo_description) ?
                    $this->checkSeoCity(str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_description)) : $description;

                $arr['keywords'] = ($model->seo_keywords) ?
                    str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_keywords) :
                    Yii::t('action', 'seo_keywords');
            } else {
                $arr['robots'] = 'noindex, nofollow';
            }
        }

        if ($model && $isFull) {
            //action/view
            $arr['title'] = ($model->seo_title) ?
                str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_title) :
                $model->title . '-' . Yii::t('action', 'Promotions') . ($model->companyName ? (' ' . $model->companyName->title) : '');

            $arr['description'] = ($model->seo_description) ?
                str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_description) : $model->description;

            $keys = [
                Yii::t('action', 'promotions'),
                Yii::t('action', 'Promotions'),
                $model->companyName ? $model->companyName->title : null
            ];

            $arr['keywords'] = ($model->seo_keywords) ?
                str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_keywords) : implode(',', $keys);

            if ($model->tags) {
                $arr['keywords'] = $arr['keywords'] . ', ' . $model->tags;
            }
        }

        return $arr;
    }

    protected function saveTegs($tegs)
    {
        $arr = explode(', ', $tegs);

        foreach ($arr as $item) {
            $model = Tag::find()->where(['like', 'title', $item])->one();

            if (!$model) {
                $model = new Tag();
            }

            $model->title = $item;
            $model->save();
        }
    }

    /**
     * @param $cat
     * @param $pages
     * @return $this
     */
    private function setIndexSeo($cat)
    {
        /** @var ActionCategory $cat */
        $view = $this->view;
        $page = Yii::$app->request->get('page', 1);

        $meta = [];

        if ($city = Yii::$app->request->city) {
            $title = ($cat and $cat->seo_title) ? $cat->seo_title : Yii::t('action', 'Action index page title {city}', ['city' => $city->title]);
            if ($page === 1) {
                $meta['keywords'] = ($cat and $cat->seo_keywords) ? $cat->seo_keywords :
                    Yii::t('action', 'Action index page keywords {city}', ['city' => $city->title]);
                $meta['description'] = ($cat and $cat->seo_description) ? $cat->seo_description :
                    Yii::t('action', 'seo_description', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            }
        } else {
            $title = ($cat and $cat->seo_title) ? $cat->seo_title : Yii::t('action', 'Action index page title Ukraine');
            if ($page === 1) {
                $meta['keywords'] = ($cat and $cat->seo_keywords) ? $cat->seo_keywords :
                    Yii::t('action', 'Action index page keywords');
                $meta['description'] = ($cat and $cat->seo_description) ? $cat->seo_description :
                    Yii::t('action', 'seo_description_no_city');
            }
        }
        $meta['title'] = SeoHelper::registerTitle($view, $title);


        if ($page !== 1) {
            $meta['robots'] = 'noindex, nofollow';
        }

        SeoHelper::registerAllMeta($view, $meta);
        SeoHelper::registerOgImage();

        return $this;
    }

    private function setViewSeo($model)
    {
        $view = $this->view;
        $seo = $this->getSeo($model, true);
        $seo['title'] .= ' - CityLife';

        SeoHelper::registerTitle($view, $seo['title']);

        SeoHelper::registerAllMeta($view, ['title' => $seo['title'], 'description' => $seo['description'], 'keywords' => $seo['keywords']]);

        if ($model->image) {
            $this->photo = Yii::$app->files->getUrl($model, 'image', 500);
        }

        SeoHelper::registerOgImage($this->photo);
    }

    /**
     * @param $archive
     * @param $pid
     * @param $cat
     * @return $this
     */
    private function setIndexBreadcrumbs($archive, $pid, $cat)
    {
        if ($archive) {
            $this->breadcrumbs = [
                ['label' => Yii::t('action', 'Promotions'), 'url' => Url::to(['action/index'])],
                ['label' => Yii::t('action', 'Archive_shares')],
            ];
        } elseif ($pid) {
            $this->breadcrumbs = [['label' => Yii::t('action', 'Promotions'), 'url' => Url::to(['action/index'])]];

            if ($archive) {
                $this->breadcrumbs[] = ['label' => Yii::t('action', 'Archive_shares'), 'url' => Url::to(['action/index', 'archive' => true])];
            }
            $this->breadcrumbs[] = ['label' => $cat->title];
        }
        return $this;
    }
}
