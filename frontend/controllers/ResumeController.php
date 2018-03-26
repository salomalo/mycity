<?php

namespace frontend\controllers;

use common\models\search\WorkResume as WorkResumeSearch;
use common\models\WorkCategory;
use common\models\WorkResume;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use frontend\helpers\SeoHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * Description of ResumeController
 *
 * @author dima
 */
class ResumeController extends Controller
{
    public $breadcrumbs = [];
    public $alias_category = '';
    public $id_category;

    public function init()
    {
        parent::init();

        $this->breadcrumbs = [
            ['label' => Yii::t('resume', 'Summary')],
        ];
    }

    public function actionIndex($pid = null)
    {
//        if (empty(Yii::$app->request->city)) {
//            throw new HttpException(404);
//        }
        if ($url = SeoHelper::redirectFromFirstPage()) {
            return $this->redirect($url, 301);
        }

        $category = WorkCategory::find()->where(['url' => $pid])->one();
        if (!empty($category)) {
            $this->alias_category = $category->url;
            $this->id_category = $category->id;
        }

        $query = WorkResumeSearch::find()->with(['category', 'user']);

        if (!empty(Yii::$app->params['SUBDOMAINID'])) {
            $query = $query->andFilterWhere(['idCity' => Yii::$app->params['SUBDOMAINID']], false);
        }
        /** @var WorkCategory $cat */
        $cat = WorkCategory::find()->where(['url' => $pid])->one();

        if ($pid && !$cat) {
            throw new HttpException(404);
        }

        $pid = ($cat) ? $cat->id : null;

        if ($pid != null) {

            if (!$cat) {
                return Yii::$app->response->redirect(Url::to(['resume/index']), 301);
            }

            define('CATEGORYID', $pid, true);

            $query->andWhere(['idCategory' => $pid]);

            $breadcrumbs = [
                ['label' => Yii::t('resume', 'Summary'), 'url' => Url::to(['resume/index'])],
                ['label' => $cat->title]
            ];
            $this->breadcrumbs = $breadcrumbs;
        }

        $countQuery = clone $query;
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
//        if (empty(Yii::$app->request->city)) {
//            throw new HttpException(404);
//        }
        $url = explode('-', $alias);

        if ($url[0] > Yii::$app->params['maxIntValue'] || !(int)$url[0]) {
            throw new HttpException(404);
        }

        /** @var WorkResume $model */
        $model = WorkResume::findOne(['id' => $url[0]]);
        if (!$model) {
            throw new HttpException(404);
        }

        $this->checkAlias($url[0], $model->url, $alias);

        define('CATEGORYID', $model->idCategory, true);
        
        $this->breadcrumbs = [
            ['label' => Yii::t('resume', 'Summary'), 'url' => Url::to(['resume/index'])],
            ['label' => $model->category->title, 'url' => Url::to(['resume/index', 'pid' => $model->category->url])],
            ['label' => $model->title]
        ];
        $this->setViewSeo($model);

        return $this->render('view', ['model' => $model]);
    }
    
    private function setIndexSeo($cat)
    {
        $view = $this->view;
        $page = (int)Yii::$app->request->post('page', 1);
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

    public function checkAlias($idUrl, $modelUrl, $alias)
    {
        $aliasFromUrl = str_replace($idUrl . '-', '', $alias);
        if ($modelUrl != $aliasFromUrl) {
            return $this->redirect($idUrl . '-' . $modelUrl, 301);
        }
    }

    private function getSeo($model, $isFull = false, $page = null)
    {
        $arr = [];

        if (!$model) {
            $arr['title'] = Yii::t('resume', 'seo_title', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            if ($page === 1) {
                $arr['description'] = Yii::t('resume', 'seo_description', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
                $arr['keywords'] = Yii::t('resume', 'seo_keywords', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            } else {
                $arr['robots'] = 'noindex, nofollow';
            }
        }

        if ($model && !$isFull) {
            $arr['title'] = ($model->seo_title_resume) ?
                str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_title_resume) :
                Yii::t('resume', 'seo_title', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            if ($page === 1) {
                $arr['description'] = ($model->seo_description_resume) ?
                    str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_description_resume) :
                    Yii::t('resume', 'seo_description', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);

                $arr['keywords'] = ($model->seo_keywords_resume) ?
                    str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_keywords_resume) :
                    Yii::t('resume', 'seo_keywords', ['cityTitle' => Yii::$app->params['SUBDOMAINTITLE']]);
            } else {
                $arr['robots'] = 'noindex, nofollow';
            }
        }

        if ($model && $isFull) {
            $arr['title'] = ($model->seo_title) ?
                str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_title) :
                Yii::t('resume', 'seo_title_full', [
                    'title' => $model->title,
                    'userName' => empty($model->user->username) ? '' : $model->user->username,
                ]);

            $arr['description'] = ($model->seo_description) ?
                str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_description) : $model->description;

            $arr['keywords'] = ($model->seo_keywords) ?
                str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $model->seo_keywords) :
                Yii::t('resume', 'seo_keywords_full', [
                    'title' => $model->title,
                    'cityTitle' => Yii::$app->params['SUBDOMAINTITLE'],
                    'userName' => empty($model->user->username) ? '' : $model->user->username,
                ]);
        }

        return $arr;
    }
}
