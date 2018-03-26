<?php

namespace backend\controllers;

use common\models\Product;
use kartik\widgets\Alert;
use Yii;
use common\models\ProductCategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\filters\AccessControl;
use common\models\Log;

/**
 * ProductCategoryController implements the CRUD actions for ProductCategory model.
 */
class ProductCategoryController extends Controller
{ 
    public $list = [];

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],    // все action
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['post'],
//                ],
//            ],
       ];
    }

    /**
     * Lists all ProductCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $post = Yii::$app->request->post('ProductCategory');
        $pid = ($post and isset($post['pid'])) ? $post['pid'] : null;

        $model = new ProductCategory();

        if ($pid) {
            $model->pid = $pid;
            /** @var ProductCategory $root */
            $root = ProductCategory::findOne($pid);
            $this->getPodCat($root);
            array_unshift($this->list, $root);
        } else {
            /** @var ProductCategory[] $roots */
            $this->list = ProductCategory::find()->roots()->select(['id', 'title', 'depth'])->orderBy('title')->all();
        }

        return $this->render('index', [
            'model' => $model,
            'list' => $this->list,
            'pid' => $pid ? $pid : null,
        ]);
    }

    public function getPodCat(ProductCategory $cat)
    {
        $this->list = $cat->children()->select(['id', 'title', 'depth'])->all();
    }

    /**
     * Displays a single ProductCategory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $pid = null)
    {
        $model = $this->findModel($id);
        $parent = $model->parents(1)->one();
        return $this->render('view', [
            'model' => $model,
            'pid' => $pid,
            'parenrTitle' => ($parent)? $parent->title : '',
        ]);
    }

    /**
     * Creates a new ProductCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductCategory();
        if ($model->load(Yii::$app->request->post())) {
            \Yii::$app->files->upload($model, 'image');
            if (!$model->pid) {
                $model->makeRoot();
                Log::addAdminLog("product category[create]  ID: {$model->id}", $model->id, Log::TYPE_PRODUCT_CATEGORY);
            } else {
                $root = ProductCategory::findOne(['id'=>$model->pid]);
                $model->appendTo($root);
                Log::addAdminLog("product category[create]  ID: {$model->id}", $model->id, Log::TYPE_PRODUCT_CATEGORY);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'parent' => '',
                'parent_title' => 'Главная категория',
            ]);
        }
    }

    public function actionAdd($id)
    {
        $model = new ProductCategory();
        $parent = ProductCategory::find()->select(['title'])->where(['id' => $id])->one();
        $parent_title = $parent['title'];
        if ($model->load(Yii::$app->request->post())) {
            \Yii::$app->files->upload($model, 'image');
            $post = Yii::$app->request->post('ProductCategory');
            if ($model->appendTo(ProductCategory::findOne($model->pid))) {
                Log::addAdminLog("product category[create]  ID: {$model->id}", $model->id, Log::TYPE_PRODUCT_CATEGORY);
                return $this->redirect(['view', 'id' => $model->id,'pid'=>$post['pid']]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'parent' => $id,
                'parent_title' => $parent_title,
            ]);
        }
    }

    /**
     * Updates an existing ProductCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $actions = '')
    {
        $status = Yii::$app->request->get('status', false);
        $model = $this->findModel($id);
        if ($actions == 'deleteImg') {
            Yii::$app->files->deleteFile($model, 'image');
            $model->image = '';
            if($model->save()) $status = Alert::TYPE_SUCCESS;
            else $status = Alert::TYPE_WARNING;
            $this->redirect(['update', 'id' => $id, 'status' => $status]);
        } elseif ($model->load(Yii::$app->request->post())) {
            Yii::$app->files->upload($model, 'image');

            if($model->save()){
                Log::addAdminLog("product category[update]  ID: {$model->id}", $model->id, Log::TYPE_PRODUCT_CATEGORY);
                return $this->redirect(['view', 'id' => $model->id,'pid'=>$model->pid]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'parent' => '',
            'status' => $status,
        ]);
    }

    public function actionUp($id, $pid = null)
    {
        $model = $this->findModel($id);
        
        $prev =  $model->prev()->one();
        if($prev){
            if ($prev->isChildOf($model)){
                Yii::$app->session->setFlash('alert-warning', 'Невожможно переместить. Элемент: ' . $prev->title . ' является дочерним элементом ' . $model->title);
            } else {
                $model->insertBefore($prev);
                Yii::$app->session->setFlash('alert-success', 'Элемент перемещен вверх.');
            }
        }
        else{
            Yii::$app->session->setFlash('alert-warning', 'Элемент находится на верхнем уровне.');
        }

        //$this->refresh();
        $model = new ProductCategory();

        if ($pid) {
            $model->pid = $pid;
            /** @var ProductCategory $root */
            $root = ProductCategory::findOne($pid);
            $this->getPodCat($root);
            array_unshift($this->list, $root);
        } else {
            /** @var ProductCategory[] $roots */
            $this->list = ProductCategory::find()->roots()->select(['id', 'title', 'depth'])->all();
        }

        return $this->render('index', [
            'model' => $model,
            'list' => $this->list,
            'pid' => $pid ? $pid : null,
        ]);
    }

    public function actionLeft($id, $pid = null)
    {
        $model = $this->findModel($id);
        
        $parent =  $model->parents(1)->one();
        if($parent && !$parent->isRoot()){
            $model->insertAfter($parent);
            Yii::$app->session->setFlash('alert-success', 'Элемент перемещен влево.');
        }
        else{
            if(!$model->isRoot()){
                $model->makeRoot();
                Yii::$app->session->setFlash('alert-success', 'Элемент перемещен влево.');
            }
            else{
                Yii::$app->session->setFlash('alert-warning', 'Корневой элемент не переносится влево.');
            }
        }

        //$this->refresh();
        $model = new ProductCategory();

        if ($pid) {
            $model->pid = $pid;
            /** @var ProductCategory $root */
            $root = ProductCategory::findOne($pid);
            $this->getPodCat($root);
            array_unshift($this->list, $root);
        } else {
            /** @var ProductCategory[] $roots */
            $this->list = ProductCategory::find()->roots()->select(['id', 'title', 'depth'])->all();
        }

        return $this->render('index', [
            'model' => $model,
            'list' => $this->list,
            'pid' => $pid ? $pid : null,
        ]);
    }

    public function actionDown($id, $pid = null)
    {
        $model = $this->findModel($id);
        
        $next =  $model->next()->one();
        if($next){
            $model->insertAfter($next);
            Yii::$app->session->setFlash('alert-success', 'Элемент перемещен вниз.');
        }
        else{
            Yii::$app->session->setFlash('alert-warning', 'Элемент находится на нижнем уровне.');
        }

        //$this->refresh();
        $model = new ProductCategory();

        if ($pid) {
            $model->pid = $pid;
            /** @var ProductCategory $root */
            $root = ProductCategory::findOne($pid);
            $this->getPodCat($root);
            array_unshift($this->list, $root);
        } else {
            /** @var ProductCategory[] $roots */
            $this->list = ProductCategory::find()->roots()->select(['id', 'title', 'depth'])->all();
        }

        return $this->render('index', [
            'model' => $model,
            'list' => $this->list,
            'pid' => $pid ? $pid : null,
        ]);
    }

    public function actionRight($id, $pid = null)
    {
        $model = $this->findModel($id);
        
        $parent =  $model->prev()->one();
        if($parent){
            if ($parent->isChildOf($model)){
                Yii::$app->session->setFlash('alert-warning', 'Невожможно переместить. Элемент: ' . $parent->title . ' является дочерним элементом ' . $model->title);
            } else {
                $model->appendTo($parent);
                Yii::$app->session->setFlash('alert-success', 'Элемент перемещен вправо.');
            }
        }
        else{
            Yii::$app->session->setFlash('alert-warning', 'Элемент находится на верхнем уровне.');
        }

        if($model->isRoot() && !$parent){
            $root = ProductCategory::find()
                    ->where('id < :id', [':id' => $model->id])
                    ->andWhere(['depth' => 0])
                    ->orderBy('id DESC')
                    ->one();
            if ($root) {
                $model->appendTo($root);
                Yii::$app->session->setFlash('alert-success', 'Элемент перемещен вправо.');
            } else {
                Yii::$app->session->setFlash('alert-warning', 'Элемент находится на верхнем уровне.');
            }
        }

        //$this->refresh();
        $model = new ProductCategory();

        if ($pid) {
            $model->pid = $pid;
            /** @var ProductCategory $root */
            $root = ProductCategory::findOne($pid);
            $this->getPodCat($root);
            array_unshift($this->list, $root);
        } else {
            /** @var ProductCategory[] $roots */
            $this->list = ProductCategory::find()->roots()->select(['id', 'title', 'depth'])->all();
        }

        return $this->render('index', [
            'model' => $model,
            'list' => $this->list,
            'pid' => $pid ? $pid : null,
        ]);
    }

    /**
     * Deletes an existing ProductCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
       $model = $this->findModel($id);
       \Yii::$app->files->deleteFile($model, 'image');
       if (!$model->isRoot() && !$model->children(1)->all()) {
            $model->delete();           
       }  else {
           $model->deleteWithChildren();  
       }
        Log::addAdminLog("product category[delete]  ID: {$id}", $id, Log::TYPE_PRODUCT_CATEGORY);

        return $this->redirect(['index']);
    }

    public function actionMerge($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $idCategory = $post['ProductCategory']['id'];

            if (Product::updateAll(['idCategory' => (int)$idCategory], ['idCategory' => (int)$id])) {
                \Yii::$app->files->deleteFile($model, 'image');
                if (!$model->children(1)->all()) {
                    $model->deleteWithChildren();
                }

                return $this->redirect(['update', 'id' => $idCategory]);
            }
        }
        return $this->redirect(['update', 'id' => $id]);
    }

    public function actionChangeRoot($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $idCategory = $post['ProductCategory']['id'];
            $rootCat = $this->findModel($idCategory);

            if ($rootCat && $model) {
                $model->prependTo($rootCat);
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }
        return $this->redirect(['update', 'id' => $model->id]);
    }

    /**
     * Finds the ProductCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function mb_ucfirst($str, $encoding='UTF-8')
    {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
               mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }

    private function getWhereLike($searchText){
        $lower = mb_strtolower($searchText,  'UTF-8');
        $upper = $this->mb_ucfirst($lower);

        return [
            'or',
            ['like', 'title', '%' . $lower . '%', false],
            ['like', 'title', '%' . $upper . '%', false]
        ];
    }

    public function actionProductCategoryList()
    {
        $pid = null;
        $where = [];
        if($searchText = Yii::$app->request->post("search")){
            $where = $this->getWhereLike($searchText);
        }
            
        if (Yii::$app->request->post("pid")) {
            $pid = Yii::$app->request->post("pid");
            $root = ProductCategory::findOne(['id' => $pid]);
            $categorylist = $root->children(1)->select(['id','title'])->andWhere($where)->orderBy('title')->all();
        }
        else{
           $categorylist = ProductCategory::find()->where($where)->roots()->orderBy('title')->all();
        }
        $parent = 0;
        if (($pid = Yii::$app->request->post("pid")) and $root = ProductCategory::findOne(['id' => $pid])) {
            $parent = $root->parents(1)->one();
            $parent = isset($parent->id) ? $parent->id : $parent;
        }
        echo Json::encode(['items' => $categorylist, 'back_id' => (int)$parent]);
    }

    public function actionCheckProductCategories()
    {
        $arr = [];
        $id = $_POST['id'];

        $id = trim($id, ',');

        $ids = explode(',', $id);

        foreach ($ids as $item){
            $model = ProductCategory::findOne((int)$item);

            if($model){
                $arr[] = [
                    'id' => $model->id,
                    'class' => '',
                    'title' => $model->title,
                ];
            }
            else{
                $arr[] = [
                    'id' => (int)$item,
                    'class' => 'id-error',
                    'title' => 'Не найденно',
                ];
            }
        }

        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }
}
