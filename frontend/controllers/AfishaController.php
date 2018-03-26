<?php
namespace frontend\controllers;

use common\models\Afisha;
use common\models\AfishaCategory;
use common\models\Business;
use common\models\City;
use common\models\KinoGenre;
use common\models\Log;
use common\models\Tag;
use common\models\User;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use frontend\helpers\SeoHelper;
use yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

class AfishaController extends Controller
{
    const AFISHA_FOR_WEEK = 'for-the-week';

    public $aliasCategory = '';
    public $breadcrumbs = [];
    public $listCityBusiness = [];
    public $genre = null;
    public $pid = null;
    public $idCategory = null;
    public $archive = false;
    public $forWeek = false;
    public $isFilm;
    public $date;
    public $time;
    public $models;
    public $photo;

    public function init()
    {
        parent::init();

        $this->isFilm = false;
        $this->breadcrumbs = [
            ['label' => Yii::t('afisha', 'Poster')],
        ];
    }
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'update'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    public function actionSoon($pid = null)
    {
        if (!Yii::$app->request->city){
            $cities = City::find()->select('id')->where(['main' => City::ACTIVE])->column();
        } else {
            $cities = [Yii::$app->params['SUBDOMAINID']];
        }

        if ($url = SeoHelper::redirectFromFirstPage()) {
            return $this->redirect($url,301);
        }

        $this->forWeek = true;
        $this->time = self::AFISHA_FOR_WEEK;
        $this->date = date('Y-m-d');
        $this->archive = false;

        $date = date('Y-m-d 23:59:59');
        $day = mb_strtolower(date('D'));

        $query = Afisha::find()
            ->with(['afishaWeekRepeat', 'afisha_category'])
            ->joinWith(['afishaWeekRepeat', 'afisha_category'])
            ->leftJoin('business',
                'business.id = ANY(afisha."idsCompany") AND business."idCity" = ANY(:city) AND afisha."isFilm" = 0',
                ['city' => $this->php_to_postgres_array($cities)]
            )
            ->leftJoin('schedule_kino', 'afisha.id = schedule_kino."idAfisha" AND afisha."isFilm" = 1')
            ->orWhere(['and',
                ['afisha."isFilm"' => 0],
                ['business."idCity"' => $cities],
                ['or',
                    ['afisha.repeat' => Afisha::REPEAT_WEEK],
                    ['>=', 'afisha."dateStart"', $date],
                ],
                ['not', ['afisha.repeat' => Afisha::REPEAT_DAY]],
                ['not', ["afisha_week_repeat.{$day}" => true]],
            ])
            ->orWhere(['and',
                ['afisha."isFilm"' => 1],
                ['schedule_kino."idCity"' => $cities],
                ['>=', 'schedule_kino."dateStart"', $date],
                ['afisha.repeat' => Afisha::REPEAT_DAY],
                ['afisha.repeat' => Afisha::REPEAT_WEEK],
            ])
            ->andWhere(['not', ['afisha.repeat' => Afisha::REPEAT_DAY]])
            ->andWhere(['not', ["afisha_week_repeat.{$day}" => true]]);

        $query->groupBy(['afisha.id', 'afisha_category.order']);

        /*
        * genre\category filter
        */
        /** @var AfishaCategory $cat */
        $cat = AfishaCategory::find()->where(['url' => $pid])->one();
        if ($pid and !$cat) {
            throw new HttpException(404);
        } elseif (!empty($cat) and ($cat->isFilm === 1) and !empty($cat->pid)) {
            $cat = AfishaCategory::find()->where(['id' => $cat->pid])->one();
        }
        $this->idCategory = ($cat) ? $cat->id : null;

        if (isset($_GET['genre'])) {
            $this->genre = $_GET['genre'];
            $this->isFilm = true;
            $this->aliasCategory = $cat->url;
            $query->innerJoin('kino_genre', 'kino_genre.id = ANY(afisha.genre) AND kino_genre.url = :url', ['url' => $this->genre]);
        } elseif ($this->idCategory) {
            define('CATEGORYID', $this->idCategory);
            $this->aliasCategory = $cat->url;
            $this->isFilm = ($cat->isFilm === 1) ? true : false;
            $subQuery = $this->isFilm ? AfishaCategory::find()->select(['id'])->where(['isFilm' => 1]) : $this->idCategory;
            $query->andWhere(['afisha_category.id' => $subQuery]);
        }
        /*
        * sorting
        */
        $query->joinWith(['afisha_category']);
        $order = [
            'afisha_category.order' => SORT_ASC,
            'afisha.repeat' => SORT_ASC,
            'afisha.order' => SORT_ASC,
            'afisha.dateStart' => SORT_ASC,
            'afisha.title' => SORT_ASC,
        ];
        $query->orderBy($order);
        /** @var Afisha[] $models */
        $models = $query->all();
        $this->setIndexBreadcrumbs($cat);
        $this->setSoonSeo($cat);
        $this->models = $models;

        if ($cat) {
            $title = Yii::t('afisha', 'Waiting events') . ' ' . $cat->title;
        } else {
            $title = Yii::t('afisha', 'Waiting events');
        }

        $business = [];
        foreach ($models as $model) {
            if ($model->idsCompany and isset($model->idsCompany[0]) and (int)$model->idsCompany[0]) {
                $business[] = (int)$model->idsCompany[0];
            }
        }
        if ($business) {
            $business = Business::find()->where(['id' => array_unique($business)])->indexBy('id')->all();
        }

        return $this->render('index', [
            'business' => $business,
            'models' => $models,
            'forToday' => false,
            'pages' => false,
            'titleCategory' => $title,
            'archive' => $this->archive,
        ]);
    }
    public function actionWeek($pid = null, $time = null)
    {
        if (!Yii::$app->request->city){
            $cities = City::find()->select('id')->where(['main' => City::ACTIVE])->column();
        } else {
            $cities = [Yii::$app->params['SUBDOMAINID']];
        }

        if ($url = SeoHelper::redirectFromFirstPage()) {
            $this->redirect(Url::to(['afisha/week']));
        }
        if ($time) {
            return $this->redirect(Url::to(['afisha/index', 'time' => $time]),301);
        }
        $this->forWeek = true;
        $this->time = self::AFISHA_FOR_WEEK;
        $this->date = date('Y-m-d');
        $this->archive = false;

        $after = time() + (60 * 60 * 24 * 7);
        $end = date('Y-m-d 00:00:00', time());
        $date = date('Y-m-d 23:59:59', $after);

        $query = Afisha::find()
            ->with(['afishaWeekRepeat', 'afisha_category'])
            ->joinWith(['afishaWeekRepeat', 'afisha_category'])
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
            ->andWhere(['afisha."isChecked"' => 1])
            ->andWhere(['or',
                ['and',
                    ['<=', 'afisha."dateStart"', $date],
                    ['>=', 'afisha."dateEnd"', $end],
                ],
                ['and',
                    ['<=', 'schedule_kino."dateStart"', $date],
                    ['>=', 'schedule_kino."dateEnd"', $end],
                ],
                ['afisha.repeat' => Afisha::REPEAT_DAY],
                ['afisha.repeat' => Afisha::REPEAT_WEEK],
            ]);

        $query->groupBy(['afisha.id', 'afisha_category.order']);

        /*
        * genre\category filter
        */
        /** @var AfishaCategory $cat */
        $cat = AfishaCategory::find()->where(['url' => $pid])->one();
        if ($pid and !$cat) {
            throw new HttpException(404);
        } elseif (!empty($cat) and ($cat->isFilm === 1) and !empty($cat->pid)) {
            $cat = AfishaCategory::find()->where(['id' => $cat->pid])->one();
        }
        $this->idCategory = ($cat) ? $cat->id : null;

        if (isset($_GET['genre'])) {
            $this->genre = $_GET['genre'];
            $this->isFilm = true;
            $this->aliasCategory = $cat->url;
            $query->innerJoin('kino_genre', 'kino_genre.id = ANY(afisha.genre) AND kino_genre.url = :url', ['url' => $this->genre]);
        } elseif ($this->idCategory) {
            define('CATEGORYID', $this->idCategory);
            $this->aliasCategory = $cat->url;
            $this->isFilm = ($cat->isFilm === 1) ? true : false;
            $subQuery = $this->isFilm ? AfishaCategory::find()->select(['id'])->where(['isFilm' => 1]) : $this->idCategory;
            $query->andWhere(['afisha_category.id' => $subQuery]);
        }
        /*
        * sorting
        */
        $query->joinWith(['afisha_category']);

        $countQuery = clone  $query;
        $limit = 10;
        $order = [
            'afisha_category.order' => SORT_ASC,
            'afisha.repeat' => SORT_ASC,
            'afisha.order' => SORT_ASC,
            'afisha.dateStart' => SORT_ASC,
            'afisha.title' => SORT_ASC,
        ];
        $query->orderBy($order);

        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => $limit
        ]);
        $pages->pageSizeParam = false;
        $query->offset($pages->offset)->limit($pages->limit);

        /** @var Afisha[] $models */
        $models = $query->all();
        $this->setIndexBreadcrumbs($cat);
        $this->setWeekSeo($cat);
        $this->models = $models;

        $cat = ($cat) ? $cat->title : null;
        $title = (!$cat) ? Yii::t('afisha', 'For_the_week') : Yii::t('afisha', 'For_the_week') .  ' ' . (isset($cat->title) ? $cat->title : $cat);

        $business = [];
        foreach ($models as $model) {
            if ($model->idsCompany and isset($model->idsCompany[0]) and (int)$model->idsCompany[0]) {
                $business[] = (int)$model->idsCompany[0];
            }
        }
        if ($business) {
            $business = Business::find()->where(['id' => array_unique($business)])->indexBy('id')->all();
        }

        return $this->render('index', [
            'business' => $business,
            'models' => $models,
            'forToday' => false,
            'titleCategory' => $title,
            'archive' => $this->archive,
            'pages' => $pages,
        ]);
    }
    public function actionNow($pid = null)
    {
        if ($url = SeoHelper::redirectFromFirstPage()) {
            return $this->redirect($url, 301);
        }

        if (!Yii::$app->request->city){
            $cities = City::find()->select('id')->where(['main' => City::ACTIVE])->column();
        } else {
            $cities = [Yii::$app->params['SUBDOMAINID']];
        }

        $end = date('Y-m-d H:i:s');
        $date = date('Y-m-d 23:59:59');
        $day = mb_strtolower(date('D', strtotime($end)));
        $query = Afisha::find()
            ->with(['afishaWeekRepeat', 'afisha_category'])
            ->joinWith(['afishaWeekRepeat', 'afisha_category'])
            ->leftJoin(
                'business',
                'business.id = ANY(afisha."idsCompany") AND business."idCity" = ANY(:city) AND afisha."isFilm" = 0',
                ['city' => $this->php_to_postgres_array($cities)]
            )
            ->where(['business."idCity"' => $cities])
            ->andWhere(['afisha."isChecked"' => 1])
            ->andWhere(['or',
                ['and',
                    ['<=', 'afisha."dateStart"', $date],
                    ['>=', 'afisha."dateEnd"', $end],
                ],
                ['afisha.repeat' => Afisha::REPEAT_DAY],
                ["afisha_week_repeat.{$day}" => true],
            ]);

        $query->groupBy(['afisha.id', 'afisha_category.order']);


        /*
        * genre\category filter
        */
        /** @var AfishaCategory $cat */
        $cat = AfishaCategory::find()->where(['url' => $pid])->one();

        if ($pid and !$cat) {
            throw new HttpException(404);
        } elseif (!empty($cat) and ($cat->isFilm === 1) and !empty($cat->pid)) {
            $cat = AfishaCategory::find()->where(['id' => $cat->pid])->one();
        }
        $this->idCategory = ($cat) ? $cat->id : null;

        if (isset($_GET['genre'])) {
            $this->genre = $_GET['genre'];
            $this->isFilm = true;
            $this->aliasCategory = $cat->url;
            $query->innerJoin('kino_genre', 'kino_genre.id = ANY(afisha.genre) AND kino_genre.url = :url', ['url' => $this->genre]);
        } elseif ($this->idCategory) {
            define('CATEGORYID', $this->idCategory);
            $this->aliasCategory = $cat->url;
            $this->isFilm = ($cat->isFilm === 1) ? true : false;
            $subQuery = $this->isFilm ? AfishaCategory::find()->select(['id'])->where(['isFilm' => 1]) : $this->idCategory;
            $query->andWhere(['afisha_category.id' => $subQuery]);
        }

        /** @var Afisha[] $models */
        $models = $query->indexBy('id')->all();

        $afisha_times = [];
        $now_in_minute = date('H') * 60 + date('i');
        foreach ($models as $model) {
            $times = [];
            if (is_array($model->times) && $model->times) {
                foreach ($model->times as $time) {
                    $time = str_replace('.', ':', $time);
                    preg_match("/(2[0-3]|[01][0-9]):([0-5][0-9])/", $time, $t);
                    if (isset($t[1]) && isset($t[2])) {
                        $minutes = $t[1] * 60 + $t[2];
                        if ($now_in_minute < $minutes) {
                            $times[] = $minutes;
                        }
                    }
                }
                if ($times) {
                    sort($times, SORT_NUMERIC);
                    $afisha_times[$model->id] = $times[0];
                }
            }
        }
        asort($afisha_times, SORT_NUMERIC);

        $sorted_models = [];
        foreach ($afisha_times as $id => $time) {
            $sorted_models[] = $models[$id];
        }

        unset($models, $afisha_times);

        $this->setIndexBreadcrumbs($cat);
        $this->setIndexSeo($cat);

        $business = [];
        foreach ($sorted_models as $model) {
            if ($model->idsCompany and isset($model->idsCompany[0]) and (int)$model->idsCompany[0]) {
                $business[] = (int)$model->idsCompany[0];
            }
        }
        if ($business) {
            $business = Business::find()->where(['id' => array_unique($business)])->indexBy('id')->all();
        }

        $this->models = $sorted_models;

        if ($cat) {
            $title = Yii::t('afisha', 'Poster_category_{cityTitle}', ['titleCategory' => $cat->title, 'cityTitle' => Yii::$app->params['SUBDOMAIN_TITLE_GE']]);
        } else {
            $title = Yii::t('afisha', 'Poster_schedule_{cityTitle}', ['cityTitle' => Yii::$app->params['SUBDOMAIN_TITLE_GE']]);
        }

        $this->setDate(date('Y-m-d'));

        return $this->render('now', [
            'business' => $business,
            'models' => $sorted_models,
            'pages' => isset($pages) ? $pages : false,
            'titleCategory' => $title,
            'archive' => $this->archive,
        ]);
    }

    public function php_to_postgres_array($phpArray)
    {
        return '{' . join(',', $phpArray) . '}';
    }

    public function actionIndex($pid = null, $time = null, $archive = false)
    {
        if ($url = SeoHelper::redirectFromFirstPage()) {
            return $this->redirect($url, 301);
        }
        if ($archive === 'archive') {
            $archive = true;
        }
        $this->setDate($time);

        $this->archive = ($archive) ? true : false;
        $limit = ($this->archive) ? 10 : 1000;

        if (!Yii::$app->request->city){
            $cities = City::find()->select('id')->where(['main' => City::ACTIVE])->column();
        } else {
            $cities = [Yii::$app->params['SUBDOMAINID']];
        }

        if (!$this->archive) {
            $end = $this->date . ' 00:00:00';
            $date = $this->date . ' 23:59:59';
            $day = mb_strtolower(date('D', strtotime($end)));
            $query = Afisha::find()
                ->with(['afishaWeekRepeat', 'afisha_category'])
                ->joinWith(['afishaWeekRepeat', 'afisha_category'])
                ->leftJoin(
                    'business',
                    'business.id = ANY(afisha."idsCompany") AND business."idCity" = ANY(:city) AND afisha."isFilm" = 0',
                    ['city' => $this->php_to_postgres_array($cities)]
                )
                ->leftJoin(
                    'schedule_kino',
                    'afisha.id = schedule_kino."idAfisha" AND schedule_kino."idCity" = ANY(:city) AND afisha."isFilm" = 1',
                    ['city' => $this->php_to_postgres_array($cities)]
                )
                ->where(['or',
                    ['business."idCity"' => $cities],
                    ['schedule_kino."idCity"' => $cities],
                ])
                ->andWhere(['afisha."isChecked"' => 1])
                ->andWhere(['or',
                    ['and',
                        ['<=', 'afisha."dateStart"', $date],
                        ['>=', 'afisha."dateEnd"', $end],
                    ],
                    ['and',
                        ['<=', 'schedule_kino."dateStart"', $date],
                        ['>=', 'schedule_kino."dateEnd"', $end],
                    ],
                    ['afisha.repeat' => Afisha::REPEAT_DAY],
                    ["afisha_week_repeat.{$day}" => true],
                ]);
        } else {
            $end = date('Y-m-d 00:00:00');
            $subQuery = Afisha::find()
                ->select(['afisha.id'])
                ->joinWith(['afishaWeekRepeat'])
                ->leftJoin(
                    'business',
                    'business.id = ANY(afisha."idsCompany") AND business."idCity" = ANY(:city) AND afisha."isFilm" = 0',
                    ['city' => $this->php_to_postgres_array($cities)]
                )
                ->leftJoin(
                    'schedule_kino',
                    '"afisha"."id" = schedule_kino."idAfisha" AND schedule_kino."idCity" = ANY(:city) AND afisha."isFilm" = 1',
                    ['city' => $this->php_to_postgres_array($cities)]
                )
                ->andWhere(['afisha."isChecked"' => 1])
                ->where(['or',
                    ['business."idCity"' => $cities],
                    ['schedule_kino."idCity"' => $cities],
                ])
                ->andWhere(['or',
                    ['>', 'afisha."dateEnd"', $end],
                    ['>', 'schedule_kino."dateEnd"', $end],
                ]);
            $query = Afisha::find()->joinWith(['afisha_category'])
                ->where(['not in', 'afisha.id', $subQuery])
                ->andWhere(['afisha.repeat' => Afisha::REPEAT_NONE]);
        }

        $query->groupBy(['afisha.id', 'afisha_category.order']);


        /*
        * genre\category filter
        */
        /** @var AfishaCategory $cat */
        $cat = AfishaCategory::find()->where(['url' => $pid])->one();

        if ($pid and !$cat) {
            throw new HttpException(404);
        } elseif (!empty($cat) and ($cat->isFilm === 1) and !empty($cat->pid)) {
            $cat = AfishaCategory::find()->where(['id' => $cat->pid])->one();
        }
        $this->idCategory = ($cat) ? $cat->id : null;

        if (isset($_GET['genre'])) {
            $this->genre = $_GET['genre'];
            $this->isFilm = true;
            $this->aliasCategory = $cat->url;
            $query->innerJoin('kino_genre', 'kino_genre.id = ANY(afisha.genre) AND kino_genre.url = :url', ['url' => $this->genre]);
        } elseif ($this->idCategory) {
            define('CATEGORYID', $this->idCategory);
            $this->aliasCategory = $cat->url;
            $this->isFilm = ($cat->isFilm === 1) ? true : false;
            $subQuery = $this->isFilm ? AfishaCategory::find()->select(['id'])->where(['isFilm' => 1]) : $this->idCategory;
            $query->andWhere(['afisha_category.id' => $subQuery]);
        }
        $countQuery = clone $query;

        /*
        * sorting
        */
        $order = [
            'afisha_category.order' => SORT_ASC,
            'afisha.repeat' => SORT_ASC,
            'afisha.order' => SORT_ASC,
            'afisha.dateStart' => SORT_ASC,
            'afisha.title' => SORT_ASC,
        ];
        $query->orderBy($order);

        if ($this->archive) {
            $pages = new Pagination([
                'totalCount' => $countQuery->count(),
                'pageSize' => $limit
            ]);
            $pages->pageSizeParam = false;
            $query->offset($pages->offset)->limit($pages->limit);
        }
        /** @var Afisha[] $models */
        $models = $query->all();
        $this->setIndexBreadcrumbs($cat);
        $this->setIndexSeo($cat);

        $business = [];
        foreach ($models as $model) {
            if ($model->idsCompany and isset($model->idsCompany[0]) and (int)$model->idsCompany[0]) {
                $business[] = (int)$model->idsCompany[0];
            }
        }
        if ($business) {
            $business = Business::find()->where(['id' => array_unique($business)])->indexBy('id')->all();
        }

        $this->models = $models;
        if ($this->archive) {
            if ($cat) {
                $title = Yii::t('afisha', 'Archive_poster') . ' ' . $cat->title;
            } else {
                $title = Yii::t('afisha', 'Archive_poster');
            }
        } else {
            if ($cat) {
                $title = Yii::t('afisha', 'Poster_category_{cityTitle}', ['titleCategory' => $cat->title, 'cityTitle' => Yii::$app->params['SUBDOMAIN_TITLE_GE']]);
            } else {
                $title = Yii::t('afisha', 'Poster_schedule_{cityTitle}', ['cityTitle' => Yii::$app->params['SUBDOMAIN_TITLE_GE']]);
            }
        }

        return $this->render('index', [
            'business' => $business,
            'models' => $models,
            'pages' => isset($pages) ? $pages : false,
            'forToday' => ($time) ? false : true,
            'titleCategory' => $title,
            'archive' => $this->archive,
        ]);
    }

    public function actionView($alias, $time = null)
    {
        if (!Yii::$app->request->city) {
            //throw new HttpException(404);
        }
        $format = 'Y-m-d';

        $time = $time ? (strtotime($time) ? date($format, strtotime($time)) : false) : null;

        $url = explode('-', $alias);
        if (($url[0] > Yii::$app->params['maxIntValue'])
            or !(int)$url[0] or ($time === false)
        ) {
            throw new HttpException(404);
        }
        $model = Afisha::findOne(['id' => $url[0]]);
        /* @var $model Afisha */
        if (!$model) {
            throw new HttpException(404);
        }

        $this->checkAlias($url[0], $model->url, $alias);

        $this->isFilm = (bool)$model->isFilm;
        $this->idCategory = $this->isFilm ? $model->idCategory : $model->category->id;
        define('CATEGORYID', $this->idCategory, true);

        $now = date($format);
        if ((int)$model->repeat === Afisha::REPEAT_DAY) {
            $showdate = Afisha::$repeat_type[Afisha::REPEAT_DAY];
        } elseif ($now >= $model->dateStart and $now <= $model->dateEnd) {
            $showdate = $now;
        } elseif ($now < $model->dateStart) {
            $showdate = date($format, strtotime($model->dateStart));
        } elseif (empty($model->dateEnd)) {
            $showdate = 'не указано';
        } else {
            $showdate = date($format, strtotime($model->dateEnd));
        }

        $this->setViewBreadcrumbs($model);
        $this->setViewSeo($model);

        $startDate = $this->getStartDate($time);

        return $this->render(($model->isFilm) ? 'view_film' : 'view', [
            'model' => $model,
            'date' => $startDate,
            'time' => $time,
            'showdate' => $showdate,
        ]);
    }

    public function actionCreate()
    {
        $model = new Afisha();

        $titleCompany = '';
        $this->date = date('Y-m-d');

        $model->scenario = 'noFilm';

        if ($model->load(Yii::$app->request->post())) {
            if ($model->idsCompany) {
                $business = Business::find()->select('title')->where(['id' => $model->idsCompany])->one();
                if ($business) {
                    $titleCompany = $business->title;
                }
            }
            $model->idsCompany = [$model->idsCompany];
            $model->always = !empty($_POST['Afisha']['always']) ? true : false;
            if ($model->save()) {
                $this->saveTegs($model->tags);
                Log::addUserLog("afisha[create] ID: {$model->id}", $model->id, Log::TYPE_AFISHA);
                return empty(Yii::$app->params['SUBDOMAINID']) ? $this->redirect(['/site/index']) : $this->redirect(['/afisha/index']);
            }
            $model->idsCompany = $model->idsCompany[0];
        }

        $this->breadcrumbs = [['label' => Yii::t('afisha', 'Poster'), 'url' => Url::to(['afisha/index'])], ['label' => Yii::t('afisha', 'Add_post')]];

        $this->view->title = Yii::t('afisha', 'Add_post') . ' - CityLife';

        return $this->render('create', [
            'model' => $model,
            'titleCompany' => $titleCompany,
        ]);
    }
    public function actionUpdate($alias)
    {
        $arr = explode('-', $alias);

        /** @var Afisha $model */
        $model = Afisha::find()->where(['id' => $arr[0], 'url' => str_replace($arr[0] . '-', '', $alias)])->one();

        if (!$model) {
            throw new HttpException(404);
        }

        if (Yii::$app->user->identity->role != User::ROLE_EDITOR) {
            if ($model->companys[0]->idUser != Yii::$app->user->identity->id) {
                throw new HttpException(404);
            }
        }

        $model->tags = explode(', ', $model->tags);

        $model->scenario = 'noFilm';
        $this->date = date('Y-m-d');

        $this->breadcrumbs = [
            [
                'label' => Yii::t('afisha', 'Poster'),
                'url' => Url::to(['afisha/index']),
            ],
            ['label' => Yii::t('afisha', 'Edit_post')],
            ['label' => $model->title],
        ];

        $checkCompany = [];
        $modelCompanyId = [];
        if ($model->isFilm) {
            $model->scenario = 'isFilm';
        } else {
            $model->scenario = 'noFilm';
            foreach ($model->companys as $item) {
                if ($item != null) {
                    $modelCompanyId[] = $item->id;
                    $checkCompany[] = [
                        'id' => $item->id,
                        'class' => '',
                        'title' => $item->title,
                        'cityTitle' => $item->city->title,
                    ];
                } else {
                    $checkCompany[] = [
                        'id' => '',
                        'class' => '',
                        'title' => '',
                        'cityTitle' => 'Не найдено',
                    ];
                }
            }
        }

        if ($model->dateStart) {
            $model->dateStart = date('Y-m-d', strtotime($model->dateStart));
        }

        if ($model->dateEnd) {
            $model->dateEnd = date('Y-m-d', strtotime($model->dateEnd));
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->dateStart) {
                $model->dateStart = date('Y-m-d H:i:s', strtotime($model->dateStart));
            }

            if ($model->dateEnd) {
                $model->dateEnd = $model->dateEnd . ' 23:59:00';
                $model->dateEnd = date('Y-m-d H:i:s', strtotime($model->dateEnd));
            }

            $model->always = !empty($_POST['Afisha']['always']) ? true : false;
            //\Yii::$app->files->upload($model, 'trailer');
            //$model->idsCompany = [$model->idsCompany];
            if ($model->save()) {
                $this->saveTegs($model->tags);
                Log::addUserLog("afisha[update] ID: {$model->id}", $model->id, Log::TYPE_AFISHA);
                return $this->redirect(Url::to(['view', 'alias' => $model->id . '-' . $model->url]));
            }
        }

        $model->idsCompany = $model->idsCompany[0];

        $this->view->title = Yii::t('action', 'Edit_post') . ' - CityLife';

        return $this->render('update', [
            'model' => $model,
            'checkCompany' => $checkCompany,
            'modelCompanyId' => $modelCompanyId,
        ]);
    }

    public function actionBusinessList($q = null, $id = null)
    {
        if (!Yii::$app->request->isAjax) {
            throw new HttpException(404);
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q) and isset(Yii::$app->user->identity->id)) {
            $q = str_replace(['"', '\'', ' '], ['', '', '%'], trim($q));
            $model = Business::find()
                ->select(['id', 'title'])
                ->orWhere(
                    'type > :typeKino',
                    [':typeKino' => Business::TYPE_KINOTHEATER]
                )
                ->orWhere('type IS NULL')
                ->andWhere(['idUser' => Yii::$app->user->identity->id])
                ->andWhere(['~~*', 'title', ('%' . $q . '%')])
                ->limit(10)
                ->asArray()
                ->all();
            $data = $model;
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = [
                'id' => $id,
                'text' => Business::findOne(['id' => $id])->title
            ];
        }
        $all = count($out['results']);
        for ($i = 0; $i < $all; $i++) {
            $out['results'][$i]['title'] = $this->getTranslation($out['results'][$i]['title']);
        }
        return $out;
    }

    private function setIndexSeo($cat)
    {
        /** @var $cat AfishaCategory */
        $view = $this->view;
        $page = (int)Yii::$app->request->get('page', 1);
        $city = Yii::$app->request->city;

        if ($city != null){
            $title = ($cat and $cat->seo_title) ? $cat->seo_title : Yii::t('afisha', 'Afisha index page title {city}', ['city' => $city->title]);
        } else {
            $title = ($cat and $cat->seo_title) ? $cat->seo_title : Yii::t('afisha', 'Afisha index page title');
        }
        $meta = ['title' => SeoHelper::registerTitle($view, $title)];

        if ($page === 1) {
            if ($city != null) {
                $meta['description'] = ($cat and $cat->seo_description) ? $cat->seo_description : Yii::t('afisha', 'seo_description', ['cityTitle' => $city->title]);
                $meta['keywords'] = ($cat and $cat->seo_keywords) ? $cat->seo_keywords : Yii::t('afisha', 'Afisha index page keywords {city}', ['city' => $city->title]);
            } else {
                $meta['description'] = ($cat and $cat->seo_description) ? $cat->seo_description : Yii::t('afisha', 'seo_description');
                $meta['keywords'] = ($cat and $cat->seo_keywords) ? $cat->seo_keywords : Yii::t('afisha', 'Afisha index page keywords');
            }
        } else {
            $meta['robots'] = 'noindex, nofollow';
        }
        SeoHelper::registerAllMeta($view, $meta);
        SeoHelper::registerOgImage();
    }
    private function setSoonSeo($cat)
    {
        /** @var $cat AfishaCategory */
        $view = $this->view;
        $page = (int)Yii::$app->request->get('page', 1);
        $city = Yii::$app->request->city;
        $city = $city == null ? new City() : $city;

        $title = SeoHelper::registerTitle($view, $cat ? "Скоро в $cat->title - {city} CityLife" : "Скоро - {city} CityLife");

        $seo = $this->getSeo($cat, false, $page);

        $meta = ['title' => $title];
        if ($page === 1) {
            $meta['description'] = "Скоро. {$seo['description']}";
            $meta['keywords'] = 'скоро, новинки, премьеры, '  . (($cat and $cat->seo_keywords) ? $cat->seo_keywords :
                Yii::t('afisha', 'Afisha index page keywords {city}', ['city' => $city->title]));
        } else {
            $meta['robots'] = $seo['robots'];
        }
        SeoHelper::registerAllMeta($view, $meta);
        SeoHelper::registerOgImage();
    }
    private function setWeekSeo($cat)
    {
        /** @var $cat AfishaCategory */
        $view = $this->view;
        $page = (int)Yii::$app->request->get('page', 1);
        $city = Yii::$app->request->city;
        $city = $city == null ? new City() : $city;

        $title = ($cat and $cat->seo_title) ? $cat->seo_title : Yii::t('afisha', 'Afisha index page title {city}', ['city' => $city->title]);
        $meta = ['title' => SeoHelper::registerTitle($view, $title)];

        $meta = ['title' => $title];
        if ($page === 1) {
            $meta['description'] = ($cat and $cat->seo_description) ? $cat->seo_description : Yii::t('afisha', 'seo_description', ['cityTitle' => $city->title]);
            $meta['keywords'] = ($cat and $cat->seo_keywords) ? $cat->seo_keywords : Yii::t('afisha', 'Afisha index page keywords {city}', ['city' => $city->title]);
        } else {
            $meta['robots'] = 'noindex, nofollow';
        }
        SeoHelper::registerAllMeta($view, $meta);
        SeoHelper::registerOgImage();
    }
    private function setViewSeo($model)
    {
        $view = $this->view;
        $seo = $this->getSeo($model, true);
        SeoHelper::registerTitle($view, $seo['title']);

        SeoHelper::registerAllMeta($view, ['title' => $seo['title'], 'description' => $seo['description'], 'keywords' => $seo['keywords']]);

        $url = $model->image ? Yii::$app->files->getUrl($model, 'image') : null;
        SeoHelper::registerOgImage($url);
    }
    private function getTranslation($json)
    {
        $v = json_decode($json, true);
        if (is_array($v)) {
            $json = !empty($v[Yii::$app->language]) ? $v[Yii::$app->language] : array_shift($v);
            while (empty($json) && count($v) > 0) {
                $json = array_shift($v);
            }
        }
        return $json;
    }

    public function setIndexBreadcrumbs($cat)
    {
        if ($this->genre) {
            $breadcrumbs = [[
                'label' => Yii::t('afisha', 'Poster'),
                'url' => Url::to(['afisha/index'])
            ]];
            if ($this->archive) {
                $breadcrumbs[] = [
                    'label' => Yii::t('afisha', 'Archive_poster'),
                    'url' => Url::to(['afisha/index', 'archive' => 'archive'])
                ];
            }
//            $breadcrumbs[] = [
//                'label' => $cat->title,
//                'url' => Url::to(['afisha/category/' . $cat->url])
//            ];
            $title = KinoGenre::find()
                         ->where(['url' => $this->genre])
                         ->select('title')
                         ->one()['title'];
            $breadcrumbs[] = ['label' => $title];
            $this->breadcrumbs = $breadcrumbs;
        } elseif ($this->idCategory) {
            $breadcrumbs = [[
                'label' => Yii::t('afisha', 'Poster'),
                'url' => Url::to(['afisha/index'])
            ]];
            if ($cat->parent) {
                $breadcrumbs[] = [
                    'label' => $cat->parent->title,
                    'url' => Url::to(
                        [
                            'afisha/index',
                            'pid' => $cat->parent->url,
                            'archive' => ($this->archive) ? 'archive' : null,
                        ]
                    )
                ];
            }
            if ($this->archive) {
                $breadcrumbs[] = [
                    'label' => Yii::t('afisha', 'Archive_poster'),
                    'url' => Url::to(['afisha/index', 'archive' => 'archive']),
                ];
            }
            $breadcrumbs[] = ['label' => $cat->title];
            $this->breadcrumbs = $breadcrumbs;
        } elseif ($this->archive) {
            $this->breadcrumbs = [
                [
                    'label' => Yii::t('afisha', 'Poster'),
                    'url' => Url::to(['afisha/index'])
                ],
                ['label' => Yii::t('afisha', 'Archive_poster')],
            ];
        }
    }
    public function setViewBreadcrumbs(Afisha $model)
    {
        if (!$model->isFilm) {
            $this->breadcrumbs = [
                [
                    'label' => Yii::t('afisha', 'Poster'),
                    'url' => Url::to(['afisha/index'])
                ]
            ];

            if (strtotime($model->dateEnd) < strtotime(date('Y-m-d'))) {
                $this->breadcrumbs[] = [
                    'label' => Yii::t('afisha', 'Archive_poster'),
                    'url' => Url::to(['afisha/index', 'archive' => 'archive'])
                ];
            }
            if ($model->category->parent) {
                $this->breadcrumbs[] = [
                    'label' => $model->category->parent->title,
                    'url' => Url::to(
                        [
                            'afisha/index',
                            'pid' => $model->category->parent->url,
                            'archive' => (strtotime($model->dateEnd) < time()) ?
                                'archive' : null
                        ]
                    )
                ];
            }
            $this->breadcrumbs[] = [
                'label' => $model->category->title,
                'url' => Url::to(
                    [
                        'afisha/index', 'pid' => $model->category->url,
                        'archive' => (strtotime($model->dateEnd) < time()) ?
                            'archive' : null
                    ]
                )
            ];
            $this->breadcrumbs[] = ['label' => $model->title];
        } else {
            $this->breadcrumbs = [[
                'label' => Yii::t('afisha', 'Poster'),
                'url' => Url::to(['afisha/index'])
            ]];

            //Получаем строку даты последнего дня окончания сеанса
            $lastEndDate = Afisha::getLastEndDate($model->id);
            //Добавляем breadcrumbs "архив"
            if (strtotime($lastEndDate) < strtotime(date('Y-m-d'))) {
                $this->breadcrumbs[] = [
                    'label' => Yii::t('afisha', 'Archive_poster'),
                    'url' => Url::to(['afisha/index', 'archive' => 'archive'])
                ];
            }

//            if ($model->category->parent) {
//                $this->breadcrumbs[] = [
//                    'label' => $model->category->parent->title,
//                    'url' => Url::to(
//                        [
//                            'afisha/index',
//                            'pid' => $model->category->parent->url
//                        ]
//                    )
//                ];
//            } else {
                $this->breadcrumbs[] = [
                    'label' => $model->category->title,
                    'url' => Url::to(
                        ['afisha/index', 'pid' => $model->category->url]
                    )
                ];
//            }
            $this->breadcrumbs[] = ['label' => $model->title];
        }
    }
    public function checkAlias($idUrl, $modelUrl, $alias)
    {
        $aliasFromUrl = str_replace("$idUrl-", '', $alias);
        if ($modelUrl != $aliasFromUrl) {
            return $this->redirect("$idUrl-$modelUrl", 301);
        }
    }
    private function getStartDate($time)
    {
        $this->setDate($time);
        $date = new \DateTime($this->date);
        $startAfisha = $date->modify('+1 day');
        $startDate['afisha'] = $startAfisha->format('Y-m-d');
        $startSchedule = $date->modify('-1 day');
        $startDate['schedule'] = $startSchedule->format('Y-m-d');
        return $startDate;
    }
    private function setDate($time = null)
    {
        if ($time and ($strtotime = strtotime($time))) {
            $time = date('Y-m-d', $strtotime);
            $this->date = $time;
            $this->time = $time;
        } else {
            $this->date = date('Y-m-d');
            $this->time = null;
        }
        return true;
    }
    private function getSeo($model, $isFull = false, $page = null)
    {
        $arr = [];
        $city = Yii::$app->request->city;
        $city = $city == null ? new City() : $city;
        $poster = Yii::t('afisha', 'Poster');

        if (!$model) {
            $arr['title'] = Yii::t('afisha', 'seo_title', ['cityTitle' => $city->title]);
            if ($page === 1) {
                $arr['description'] = Yii::t('afisha', 'seo_description', ['cityTitle' => $city->title]);
                $arr['keywords'] = Yii::t('afisha', 'seo_keywords', ['cityTitle' => $city->title]);
            } else {
                $arr['robots'] = 'noindex, nofollow';
            }
        }

        if ($model && !$isFull) {
            $arr['title'] = $model->seo_title ? "$model->seo_title - CityLife" :
                Yii::t('afisha', 'seo_title', ['cityTitle' => $city->title]) . ' - CityLife';
            if ($page === 1) {
                $arr['description'] = $model->seo_description ? $model->seo_description : Yii::t('afisha', 'seo_description', ['cityTitle' => $city->title]);
                $arr['keywords'] = $model->seo_keywords ? $model->seo_keywords : Yii::t('afisha', 'seo_keywords', ['cityTitle' => $city->title]);
            } else {
                $arr['robots'] = 'noindex, nofollow';
            }
        }

        if ($model && $isFull) {
            $arr['title'] = ($model->seo_title) ? "$model->seo_title - CityLife" :
                "{$model->title} {city}. {$poster} {$model->category->title} {city} - CityLife";

            $arr['description'] = ($model->seo_description) ? $model->seo_description : Yii::t('afisha', 'seo_description_full', [
                'title' => $model->title,
                'cityTitle' => $city->title,
                'categoryTitle' => $model->category->title,
            ]);

            $keys = [Yii::t('afisha', 'events'), Yii::t('afisha', 'poster'), $model->title, $city->title];

            $arr['keywords'] = ($model->seo_keywords) ? $model->seo_keywords : implode(',', $keys);

            if ($model->tags) {
                $arr['keywords'] = "{$arr['keywords']}, {$model->tags}";
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
}
