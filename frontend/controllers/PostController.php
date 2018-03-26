<?php

namespace frontend\controllers;

use common\models\Business;
use common\models\File;
use common\models\Post;
use common\models\PostCategory;
use common\models\search\Post as PostSearch;
use frontend\behaviors\ViewBehavior;
use frontend\components\traits\BusinessTrait;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use frontend\helpers\SeoHelper;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * BusinessController implements the CRUD actions for Business model.
 *
 * @property Post $model
 */
class PostController extends Controller
{
    use BusinessTrait;

    public $listAddress = [];
    public $breadcrumbs = [];
    public $categoryTitle = '';
    public $alias_category = '';
    public $model;
    private $model_id, $alias, $photo;

    public function actionView($alias)
    {
        /**@var self $model*/
        $model = $this->setId($alias)->findModel();
        if (!($model instanceof self)) {
            return $model;
        }
        return
            $model->setViewSeo()
            ->setViewBreadcrumbs()
            ->attachViewBehavior()
            ->render('view', [
                'model' => $this->model,
                'listAddress' => $this->listAddress,
                'photo' => ($this->photo) ? $this->photo : null,
            ]);
    }

    public function actionIndex($pid = null)
    {
        if ($url = SeoHelper::redirectFromFirstPage()) {
            $this->redirect($url, 301);
        }
        $query = PostSearch::find()->with(['countView']);
        if (!empty(Yii::$app->params['SUBDOMAINTITLE'])) {
            $query->where(['idCity' => Yii::$app->params['SUBDOMAINID']]);
            $query->andWhere(['onlyMain' => false]);
            $query->orWhere(['allCity' => true]);
        } else {
            $query->where(['onlyMain' => true]);
        }
        /** @var PostCategory $cat */
        $cat = PostCategory::find()->where(['url' => $pid])->one();
        if ($pid and !$cat) {
            throw new HttpException(404);
        }
        $this->alias_category = ($cat) ? $pid : '';
        $pid = ($cat)? $cat->id : null;
        if ($pid) {
            $cat = PostCategory::find()->select(['title'])->where(['id' => $pid])->one();
            if (!$cat) {
                return Yii::$app->response->redirect(Yii::$app->urlManager->createUrl('post/index'), 301);
            }
            define('CATEGORYID', $pid, true);
            $this->categoryTitle = $cat->title;
            $this->breadcrumbs[] = ['label' => Yii::t('post', 'Posts'), 'url' => Url::to(['post/index'])];
            $this->breadcrumbs[] = ['label' => $cat['title']];
            $query->andWhere(['idCategory' => $pid]);
        } else {
            $this->breadcrumbs[] = ['label' => Yii::t('post', 'Posts')];
        }
        $query->andWhere(['status' => Post::TYPE_PUBLISHED]);

        $countQuery = clone $query;
        $query->groupBy('post.id')->orderBy(['post.id' => SORT_DESC]);

        $query->joinWith('comment')->select(['post.*', 'comment_count' => 'COUNT(comment.id)']);
        if ($sort = Yii::$app->request->get('sort')) {
            $order = SORT_ASC;
            if (mb_substr($sort, 0, 1) === '-') {
                $order = SORT_DESC;
                $sort = mb_substr($sort, 1, mb_strlen($sort));
            }
            if ($sort !== 'views') {
                throw new HttpException(404);
            }

            $query->joinWith(['countView'])->addSelect(['countViews' => 'COALESCE(count_views.count, 0)'])
                ->addGroupBy(['countViews'])->orderBy(['countViews' => $order]);
        }

        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
        $pages->pageSizeParam = false;
        $query->offset($pages->offset)->limit($pages->limit);

        $models = $query->all();
        $this->setIndexSeo($cat);

        return $this->render('category', [
            'models' => $models,
            'id_category' => $cat? $cat->id : null,
            'categoryTitle' => $this->categoryTitle,
            'pages' => $pages
        ]);
    }

    public function actionBusinessBlogIndex($alias){
        $url = explode('-', $alias, 2);
        $url[0] = (int)$url[0];

        if (!$url[0]) {
            throw new HttpException(404);
        }

        $this->businessModel = Business::find()->where(['id' => (int)$url[0]])->one();
        if (!$this->businessModel){
            throw new HttpException(404);
        }
        $this->setIndexBreadcrumbs($this->businessModel);
        $this->initTemplate();
        $view = $this->view;
        $title = 'Нововсти' . $this->businessModel->title;
        SeoHelper::registerTitle($view, $title);

        $query = Post::find()
            ->where(['business_id' => $this->businessModel->id])
            ->groupBy('id')
            ->orderBy(['id' => SORT_DESC]);

        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => 8,
            'pageSizeParam' => false
        ]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'business' => $this->businessModel,
            'title' => $title,
        ]);
    }

    public function actionViewBusinessPost($alias, $aliasPost){
        $url = explode('-', $alias, 2);

        $this->businessModel = Business::find()->where(['id' => (int)$url[0]])->one();
        if (!$this->businessModel){
            throw new HttpException(404);
        }

        $model = $this->getModelByAlias($aliasPost);
        $this->setViewByBusinessBreadcrumbs($this->businessModel, $model);
        $this->initTemplate();
        $view = $this->view;
        SeoHelper::registerTitle($view, $model->title . ' - Новости - CityLife');

        return $this->render('view', [
            'model' => $model,
            'business' => $this->businessModel,
        ]);
    }

    /**
     * @param $aliasPost
     * @return null|Post
     * @throws HttpException
     */
    private function getModelByAlias($aliasPost){
        $url = explode('-', $aliasPost, 2);
        $this->model_id = isset($url[0]) ? $url[0] : null;
        $this->alias = isset($url[1]) ? $url[1] : null;
        if(!$this->model_id or !(int)$this->model_id or ($this->model_id > Yii::$app->params['maxIntValue'])) {
            throw new HttpException(404);
        }

        $model = Post::findOne($this->model_id);
        if (!$model){
            throw new HttpException(404);
        }

        return $model;
    }

    private function setId($alias)
    {
        $url = explode('-', $alias, 2);
        $this->model_id = isset($url[0]) ? $url[0] : null;
        $this->alias = isset($url[1]) ? $url[1] : null;
        if(!$this->model_id or !(int)$this->model_id or ($this->model_id > Yii::$app->params['maxIntValue'])) {
            throw new HttpException(404);
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function findModel()
    {
        $this->model = Post::findModel($this->model_id);
        if (!$this->model) return Yii::$app->getResponse()->redirect(['post/index'], 301);

        if($this->model->url !== $this->alias){
            return $this->redirect($this->model_id . '-' . $this->model->url, 301);
        }
        if($this->model->category) {
            define('CATEGORYID', $this->model->category->id, TRUE);
        }
        $this->listAddress[] = [
            'lat' => $this->model->lat,
            'lon' => $this->model->lon,
            'title' => str_replace(['"', "'"], ['\"', '\"'], $this->model->title),
            'link' => str_replace(['"', "'"], ['\"', '\"'], $this->model->address),
        ];
        return $this;
    }

    /**
     * @return $this
     */
    private function setViewSeo()
    {
        $seo = $this->getSeo($this->model , true);
        $view = $this->view;
        SeoHelper::registerTitle($view, ArrayHelper::getValue($seo, 'title', $this->model->title));
        SeoHelper::registerAllMeta($view, ['title' => $seo['title'], 'description' => $seo['description'], 'keywords' => $seo['keywords']]);

        $url = $this->model->image ? Yii::$app->files->getUrl($this->model, 'image') : null;
        SeoHelper::registerOgImage($url);

        return $this;
    }

    /**
     * @param $pages
     * @param $cat PostCategory
     */
    private function setIndexSeo($cat)
    {
        $view = $this->view;
        $page = (int)Yii::$app->request->get('page', 1);
        $city = Yii::$app->request->city;


        if ($city) {
            $title = Yii::t('post', 'Post index page title {city}', ['city' => $city->title]);
            $description = Yii::t('post', 'seo_description', ['cityTitle' => $city->title]);
            $keywords = Yii::t('post', 'seo_keywords', ['cityTitle' => $city->title]);
        } else {
            $title = Yii::t('post', 'seo_title_main');
            $description = Yii::t('post', 'seo_description_main');
            $keywords = Yii::t('post', 'seo_keywords_main');
        }

        if ($cat) {
            !$cat->seo_title ?: ($title = $cat->seo_title);
            !$cat->seo_description ?: ($description = $cat->seo_description);
            !$cat->seo_keywords ?: ($keywords = $cat->seo_keywords);
        }

        $meta = ['title' => SeoHelper::registerTitle($view, $title)];
        if ($page === 1) {
            $meta['description'] = $description;
            $meta['keywords'] = $keywords;
        } else {
            $meta['robots'] = 'noindex, nofollow';
        }

        SeoHelper::registerAllMeta($view, $meta);
        SeoHelper::registerOgImage();
    }

    /**
     * @return $this
     */
    private function setViewBreadcrumbs()
    {
        $this->breadcrumbs[] = ['label' => Yii::t('post', 'Posts'), 'url' => Url::to(['post/index'])];
        $this->breadcrumbs[] = ['label' => $this->model->title];

        return $this;
    }

    /**
     * @return $this
     */
    private function attachViewBehavior()
    {
        $this->model->attachBehavior('view', [
            'class' => ViewBehavior::className(),
            'type' => File::TYPE_POST,
            'id' => $this->model->id,
        ]);

        return $this;
    }
    
    private function getSeo($model, $isFull = false)
    {
        $arr = [];

        $title = ArrayHelper::getValue(Yii::$app->params, 'SUBDOMAINTITLE');
        $post = Yii::t('post', 'Posts');

        if (!$model) {
            // все новости
            $arr['title'] = $title ? Yii::t('post', 'seo_title', ['cityTitle' => $title]) : Yii::t('post', 'seo_title_main');
            $arr['description'] = $title ? Yii::t('post', 'seo_description', ['cityTitle' => $title]) : Yii::t('post', 'seo_description_main');
            $arr['keywords'] = $title ? Yii::t('post', 'seo_keywords', ['cityTitle' => $title]) : Yii::t('post', 'seo_keywords_main');
        }

        if ($model && !$isFull) {
            $arr['title'] = $model->seo_title ? $model->seo_title : Yii::t('post', 'seo_title', ['cityTitle' => $title]);
            $arr['description'] = $model->seo_description ? $model->seo_description : Yii::t('post', 'seo_description', ['cityTitle' => $title]);
            $arr['keywords'] = $model->seo_keywords ? $model->seo_keywords : Yii::t('post', 'seo_keywords', ['cityTitle' => $title]);
        }

        if ($model && $isFull) {
            $arr['title'] = $model->seo_title ? $model->seo_title : "{$model->title} - $post $title - CityLife";
            $arr['description'] = $model->seo_description ? $model->seo_description : $model->shortText;
            $keys = [Yii::t('post', 'Posts'), Yii::t('post', 'posts'), $model->title];
            $arr['keywords'] = $model->seo_keywords ? $model->seo_keywords : implode(',', $keys);

            if ($model->tags) {
                $arr['keywords'] = "{$arr['keywords']}, {$model->tags}";
            }
        }

        return $arr;
    }

    public function setIndexBreadcrumbs($business){
        $alias = "{$business->id}-{$business->url}";
        $this->breadcrumbs = [[
            'label' => $business->title,
            'url' => Url::to(['/business/view', 'alias' => $alias])
            ]
        ];

        $this->breadcrumbs[] = ['label' => 'Новости'];
    }

    /**
     * @param $business Business
     * @param $model Post
     */
    public function setViewByBusinessBreadcrumbs($business, $model){
        $alias = "{$business->id}-{$business->url}";
        $this->breadcrumbs = [[
            'label' => $business->title,
            'url' => Url::to(['/business/view', 'alias' => $alias])
            ]
        ];


        $url = Url::to(['/business/' . $alias . '/' . 'blog']);
        $this->breadcrumbs[] = ['label' => 'Новости', 'url' => $url];
        $this->breadcrumbs[] = ['label' => $model->title];
    }
}
