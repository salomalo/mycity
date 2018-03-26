<?php

namespace frontend\controllers;

use common\extensions\ViewCounter\ProductViewCounter;
use common\models\Ads;
use common\models\File;
use common\models\Product;
use common\models\ProductCategory;
use common\models\StarRating;
use Exception;
use frontend\behaviors\ViewBehavior;
use frontend\helpers\SeoHelper;
use yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\sphinx\Query;
use yii\web\Controller;
use yii\web\HttpException;
use yii\db\Exception as DbException;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    public $categoryTitle = 'Товары';
    public $id_category = null;
    public $alias_category = '';
    public $breadcrumbs = [];
    public $filter = [];
    public $selectedPrice = [];

    const IS_FILTER = 1;
    const TYPE_DROP_DOWN = 0;
    
    public function init()
    {
        parent::init();
        $this->breadcrumbs = [
            ['label' => Yii::t('product', 'Goods')],
        ];
    }

    public function actionIndex($pid = null)
    {
        if ($url = SeoHelper::redirectFromFirstPage()) {
            return $this->redirect($url, 301);
        }

        /** @var ProductCategory $category */
        $category = $pid ? ProductCategory::find()->where(['url' => $pid])->one() : null;

        $query = Product::find();

        if ($category) {
            define('CATEGORYID', $category->id, true);

            $categoriesId = $category->children()->select('id')->column();
            $categoriesId[] = $category->id;
            $query->andWhere(['idCategory' => $categoriesId]);

            SeoHelper::registerAllMeta($this->view, [
                'title' => $category->seo_title,
                'keywords' => $category->seo_keywords,
                'description' => $category->seo_description,
            ]);
            SeoHelper::registerTitle($this->view, $category->seo_title);
        } elseif ($pid) {
            $this->redirect(['index'], 301);
        } elseif ($pid == null){
            SeoHelper::registerAllMeta($this->view, [
                'title' => Yii::t('product', 'Goods'),
//                'keywords' => $category->seo_keywords,
//                'description' => $category->seo_description,
            ]);
            SeoHelper::registerTitle($this->view, Yii::t('product', 'Goods'));
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

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
        $pages->pageSizeParam = false;
        $models = $query->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'category' => $category,
        ]);
    }

    public function actionSearch($pid = null)
    {
        throw new HttpException(404);

        if (!Yii::$app->request->post('search_text')) {
            return $this->redirect(['index'], 301);
        }
        $text = Yii::$app->request->post('search_text');
//        echo \yii\helpers\BaseVarDumper::dump($text, 10, true); die();
        $query = new Query;
        //$query->select('id, title')->from('product')->match('GOODYEAR');
        $query->from('product')->match($text)->groupBy('idproduct');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        return $this->render('search', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($alias, $tab = null)
    {
        $url = explode('-', $alias, 2);
        /** @var Product $model */
        $model = Product::find()->where(['_id' => $url[0]])->one();
        if (!$model) {
            return Yii::$app->response->redirect(['product/index'],301);
        }
        
        $seo_description = !$model->category ? $model->seo_description : $model->category->seo_description;
        $seo_keywords = !$model->category ? $model->seo_keywords : $model->category->seo_keywords;
                
        $this->getView()->registerMetaTag([
            'name'=>'description',
            'content'=> str_replace('{city}', Yii::$app->params['SUBDOMAINTITLE'], $seo_description)
        ]);
        $this->getView()->registerMetaTag([
            'name'=>'keywords',
            'content'=>$seo_keywords,
        ]);
         
        $this->id_category = $model->idCategory;
        define('CATEGORYID', $model->idCategory, TRUE);
        
        /** @var ProductCategory $category */
        $category = ProductCategory::find()->where(['id' => $model->idCategory])->one();

        if ($category) {
            $parent = $category->parents()->all();
            foreach ($parent as $item) {
                $breadcrumbs[] = ['label' => $item['title'], 'url' => ['product/index', 'pid' => $item['url']]];
            }
            $breadcrumbs[] = ['label' => $category->title, 'url' => ['product/index', 'pid' => $category->url]];
        }

        $breadcrumbs[] = ['label' => $model->title];
        $this->breadcrumbs = $breadcrumbs;
        
        
        $model->attachBehavior('view', [
            'class' => ViewBehavior::className(),
            'type' => File::TYPE_PRODUCT,
            'id' => $model->_id,
            'countMonth' => true
        ]);

        ProductViewCounter::widget(['item' => $model]);

        return $this->render('view', [
            'model' => $model,
            'tab' => $tab,
            'minPrice' => $this->getMinPrice($model->_id),
            'maxPrice' => $this->getMaxPrice($model->_id),
        ]);
    }
    
    public function getMinPrice($id)
    {
        return Ads::find()->where(['model' => (string)$id])->min('price');
    }
    
    public function getMaxPrice($id)
    {
        return Ads::find()->where(['model' => (string)$id])->max('price');
    }

    public function actionRatingChange()
    {
        $value = Yii::$app->request->post('value');
        /** @var $model Ads */
        if ($id = Yii::$app->request->post('id')) {
            $model = Product::find()->where(['_id' => $id])->one();
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
                'object_type' => File::TYPE_PRODUCT,
            ])->one();
            if (!$history) {
                $history = new StarRating([
                    'user_id' => Yii::$app->user->identity->id,
                    'object_id' => $id,
                    'object_type' => File::TYPE_PRODUCT,
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

            //Yii::$app->logger->rateTrigger(Product::className(), (string)$model->_id, DetailLogObjectType::TYPE_PRODUCT);

            return json_encode(['error' => false, 'rating' => $model->rating]);
        }
    }
}
