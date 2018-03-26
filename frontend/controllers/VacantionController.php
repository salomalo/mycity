<?php

namespace frontend\controllers;

use common\models\File;
use common\models\search\WorkVacantion as WorkVacantionSearch;
use common\models\WorkCategory;
use common\models\WorkVacantion;
use frontend\behaviors\ViewBehavior;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use frontend\helpers\SeoHelper;
use yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * Description of VacantionController
 *
 * @author dima
 */
class VacantionController extends Controller
{
    public $breadcrumbs = [];
    public $alias_category = '';
    public $id_category;

    public function init()
    {
        parent::init();

        $this->breadcrumbs = [
            ['label' => Yii::t('vacantion', 'Vacantions')],
        ];
    }

    public function actionIndex($pid = null)
    {
        if ($url = SeoHelper::redirectFromFirstPage()) {
            return $this->redirect($url, 301);
        }
        $query = WorkVacantionSearch::find()->with(['category', 'company', 'countView']);

        if ($city = Yii::$app->request->city) {
            $query->andWhere(['idCity' => $city->id]);
        }

        $cat = null;
        if ($pid) {
            /** @var WorkVacantion $cat */
            $cat = WorkCategory::find()->where(['url' => $pid])->one();

            if (!$cat) {
                return Yii::$app->response->redirect(['/vacantion/index'], 301);
            }
            $this->alias_category = $cat->url;
            $this->id_category = $cat->id;

            $pid = $cat->id;

            define('CATEGORYID', $pid, true);

            $query->andWhere(['idCategory' => $pid]);

            $breadcrumbs = [
                ['label' => Yii::t('vacantion', 'Vacantions'), 'url' => url::to(['vacantion/index'])],
                ['label' => $cat->title]
            ];
            $this->breadcrumbs = $breadcrumbs;
        }

        $countQuery = clone $query;

        if ($sort = Yii::$app->request->get('sort')) {
            $order = SORT_ASC;
            if (mb_substr($sort, 0, 1) === '-') {
                $order = SORT_DESC;
                $sort = mb_substr($sort, 1, mb_strlen($sort));
            }
            if ($sort !== 'views') {
                throw new HttpException(404);
            }

            $query->joinWith(['countView'])->select(['work_vacantion.*', 'countViews' => 'COALESCE(count_views.count, 0)'])
                ->groupBy(['work_vacantion.id', 'countViews'])->orderBy(['countViews' => $order]);
        }

        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
        $pages->pageSizeParam = false;
        $query->offset($pages->offset)->limit($pages->limit);
        $models = $query->all();

        $this->setIndexSeo($cat);

        return $this->render('index', [
            'pages' => $pages,
            'models' => $models,
            'titleCategory' => ($cat) ? $cat->title : null
        ]);
    }

    public function actionView($alias)
    {
        //if (empty(Yii::$app->request->city)) throw new HttpException(404);

        $url = explode('-', $alias);

        if ($url[0] > Yii::$app->params['maxIntValue'] || !(int)$url[0]) {
            throw new HttpException(404);
        }

//        $model = WorkVacantion::findOne(['url'=>$alias]);
        $model = WorkVacantion::findOne(['id' => $url[0]]);
        if (!$model) {
//            Yii::$app->getResponse()->redirect(['vacantion/index']);
            throw new HttpException(404);
        }

        $this->checkAlias($url[0], $model->url, $alias);

        define('CATEGORYID', $model->idCategory, TRUE);

        $this->breadcrumbs = [
            ['label' => Yii::t('vacantion', 'Vacantions'), 'url' => Url::to(['vacantion/index'])],
            ['label' => $model->category->title, 'url' => Url::to(['vacantion/index', 'pid' => $model->category->url])],
            ['label' => $model->title]
        ];

        $model->attachBehavior('view', [
            'class' => ViewBehavior::className(),
            'type' => File::TYPE_WORK_VACANTION,
            'id' => $model->id,
        ]);

        $this->setViewSeo($model);

        return $this->render('view', ['model' => $model]);
    }

    public function checkAlias($idUrl, $modelUrl, $alias)
    {
        $aliasFromUrl = str_replace($idUrl . '-', '', $alias);
        if ($modelUrl != $aliasFromUrl) {
            return $this->redirect($idUrl . '-' . $modelUrl, 301);
        }
        return null;
    }

    private function getSeo($model, $isFull = false, $page = null)
    {
        $arr = [];

        if (!$model) {
            $arr['title'] = Yii::t('vacantion', 'seo_title', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            if ($page === 1) {
                $arr['description'] = Yii::t('vacantion', 'seo_description', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
                $arr['keywords'] = Yii::t('vacantion', 'seo_keywords', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            } else {
                $arr['robots'] = 'noindex, nofollow';
            }
        }

        if ($model && !$isFull) {
            $arr['title'] = ($model->seo_title) ?
                str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_title) :
                Yii::t('vacantion', 'seo_title', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            if ($page === 1) {
                $arr['description'] = ($model->seo_description) ?
                    str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_description) :
                    Yii::t('vacantion', 'seo_description', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
                $arr['keywords'] = ($model->seo_keywords) ?
                    str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_keywords) :
                    Yii::t('vacantion', 'seo_keywords', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            } else {
                $arr['robots'] = 'noindex, nofollow';
            }
        }

        if ($model && $isFull) {
            $arr['title'] = ($model->seo_title) ?
                str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_title) :
                Yii::t('vacantion', 'seo_title_full', [
                    'title' => $model->title,
                    'cityTitle' => Yii::$app->params['SUBDOMAINTITLE'],
                    'companyTitle' => empty($model->company->title) ? '' : $model->company->title,
                ]);

            $arr['description'] = ($model->seo_description) ?
                str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_description) :
                Yii::t('vacantion', 'seo_description_full', [
                    'title' => $model->title,
                    'cityTitle' => Yii::$app->params['SUBDOMAINTITLE'],
                    'companyTitle' => empty($model->company->title) ? '' : $model->company->title,
                ]);

            $arr['keywords'] = ($model->seo_keywords) ?
                str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_keywords) :
                Yii::t('vacantion', 'seo_keywords_full', [
                    'title' => $model->title,
                    'cityTitle' => Yii::$app->params['SUBDOMAINTITLE'],
                    'companyTitle' => empty($model->company->title) ? '' : $model->company->title,
                ]);
        }

        return $arr;
    }

    private function setIndexSeo($cat)
    {
        $view = $this->view;
        $page = (int)Yii::$app->request->get('page', 1);
        $seo = $this->getSeo($cat, false, $page);

        $title = SeoHelper::registerTitle($view, SeoHelper::addPageCount($page, $seo['title']) . ' - CityLife');

        $meta = ['title' => $title];
        if ($page === 1) {
            $meta['description'] = SeoHelper::addPageCount($page, $seo['description']);
            $meta['keywords'] = $seo['keywords'];
        } else {
            $meta['robots'] = $seo['robots'];
        }
        SeoHelper::registerAllMeta($view, $meta);
        SeoHelper::registerOgImage();
    }

    private function setViewSeo($model)
    {
        $view = $this->view;
        $seo = $this->getSeo($model, true);

        SeoHelper::registerTitle($view, $seo['title'] . ' - CityLife');
        SeoHelper::registerAllMeta($view, ['title' => $seo['title'], 'description' => $seo['description'], 'keywords' => $seo['keywords']]);

        SeoHelper::registerOgImage();
    }
}
