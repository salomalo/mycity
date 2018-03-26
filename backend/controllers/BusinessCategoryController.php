<?php

namespace backend\controllers;

use common\models\Log;
use common\models\BusinessProductCategory;
use common\models\ProductCategory;
use Yii;
use common\models\BusinessCategory;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\filters\AccessControl;

/**
 * BusinessCategoryController implements the CRUD actions for BusinessCategory model.
 */
class BusinessCategoryController extends Controller
{
    public $list = [];
    public $cacheKey = 'BusinessCategoryController_index_list_';
    public $cacheKeyBusinessCategoryRoots = 'BusinessCategoryController_view_index_BusinessCategory_roots_';
    public $cacheKeyCount = 'BusinessCategoryController_count_';
    
    public function init() {
        parent::init();
        
        $this->cacheKey .= Yii::$app->language;
        $this->cacheKeyBusinessCategoryRoots .= Yii::$app->language;
    }

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
     * Lists all BusinessCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new BusinessCategory();
 
        if ($model->load(Yii::$app->request->get()) && $model->pid > 0) {  
            $root = BusinessCategory::findOne($model->pid);
            $this->list[] = ['id'=>$root->id, 'title'=>$root->title, 'depth' => $root->depth];
//                'countBusiness' => ($root->conutBusiness)? '('.$root->conutBusiness.')' : ''];   
            $this->getPodCat($root);
        }
        else{
            $roots = BusinessCategory::find()->roots()->addOrderBy('title')->all();
            
            foreach ($roots as $item)
            {
                    if($item->isRoot()){
                        $this->list[] = ['id'=>$item->id, 'title'=>$item->title, 'depth' => $item->depth];
//                            'countBusiness' => ($a->conutBusiness)? '('.$a->conutBusiness.')' : ''];    
                        $this->getPodCat($item);
                }          
            }
        }
//        $query = BusinessCategory::find()->addOrderBy('root, lft');
        
//        $searchModel = new BusinessCategorySearch;
//        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
//        $model = new BusinessCategory();
//        
//        $where = ['pid'=> null];
//        $cache = Yii::$app->cache;
//        
//        if ($model->load(Yii::$app->request->get()) && $model->pid) {   
//            $where=['id'=>$model->pid];    
//        }
//       
//        if(empty($where['id'])){
//            $list = $cache->get($this->cacheKey);
//        }
//        
//        if(empty($list)){
//            $list = [];
//            foreach (BusinessCategory::find()->where($where)->orderBy('title ASC')->batch(100) as $b){
//
//                foreach ($b as $item){
//                    $list = array_merge($list, $this->getChildren($item));
//                    unset($item);
//                }
//            }
//            
//            if(empty($where['id'])){
//                $cache->set($this->cacheKey, $list);
//            }
//        }  
//             
//        $businessCategoryRoots = $cache->get($this->cacheKeyBusinessCategoryRoots);
//            if(!$businessCategoryRoots){
//                $businessCategoryRoots = BusinessCategory::find()->where(['pid'=>null])->orderBy('title')->all();
//                $cache->set($this->cacheKeyBusinessCategoryRoots, $businessCategoryRoots);
//            }
         
        return $this->render('index', [
            'model' => $model,
            'list' => $this->list,
//            'query' => $query
//            'dataProvider' => $dataProvider,
//            'searchModel' => $searchModel,
//            'maxNested' => $this->maxNested,
//            'businessCategoryRoots' => $businessCategoryRoots
        ]);
    }
    
    public function getPodCat($cat){
        
        $cache = Yii::$app->cache;
        
        foreach ($cat->children()->all() as $ch)
        {
            $key = $this->cacheKeyCount . $ch->id;
            $count = $cache->get($key);
            
            if(!$count){
                $temp = $ch->getCountBusiness();
                $count = ($temp)? '('.$temp.')' : '';
                
                $cache->set($key, $count); 
            }
            
            $this->list[]= [
                'id'=>$ch->id,
                'title'=>$ch->title,
                'depth' => $ch->depth,
                'countBusiness' => $count
                    ];
        }
    }

    /**
     * Displays a single BusinessCategory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $parent = $model->parents(1)->one();
        return $this->render('view', [
            'model' => $model,
            'parenrTitle' => ($parent)? $parent->title : '',
        ]);
        
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
    }

    /**
     * Creates a new BusinessCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BusinessCategory;

        if ($model->load(Yii::$app->request->post())) {
            
            \Yii::$app->files->upload($model, 'image');
            
            if(!$model->pid){
                if($model->makeRoot()){
                    Log::addAdminLog("business category[create]  ID: {$model->id}", $model->id, Log::TYPE_BUSINESS_CATEGORY);
                }
            }
            else{
                $root = BusinessCategory::findOne(['id'=>$model->pid]);
                if($model->appendTo($root)){
                    Log::addAdminLog("business category[create]  ID: {$model->id}", $model->id, Log::TYPE_BUSINESS_CATEGORY);
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
            
//            if($model->save()){
//                
//                $cache = Yii::$app->cache;
//                $cache->delete($this->cacheKey); 
//                $cache->delete($this->cacheKeyBusinessCategoryRoots);
//                Log::addLog('business category[create]', "ID: {$model->id}");
//                return $this->redirect(['view', 'id' => $model->id]);
//            }
            
        } 
        
        return $this->render('create', [
            'model' => $model,
            'parent' => '',
            'parent_title' => 'Главная категория',
        ]);
       
    }
    
    public function actionAdd($id)
    {
        $model = new BusinessCategory();
        $parent = BusinessCategory::find()->select(['title'])->where(['id'=>$id])->one();
        if ($model->load(Yii::$app->request->post())) {
            \Yii::$app->files->upload($model, 'image');
            $post = Yii::$app->request->post('BusinessCategory');
            if($model->appendTo(BusinessCategory::findOne($model->pid))){
                Log::addAdminLog("business category[create]  ID: {$model->id}", $model->id, Log::TYPE_BUSINESS_CATEGORY);
                return $this->redirect(['view', 'id' => $model->id,'pid'=>$post['pid']]);
            }
              
        } else {
         return $this->render('create', [
                'model' => $model,
                'parent' => $id,
                'parent_title' => $parent->title,
            ]);
        }
    }

    /**
     * Updates an existing BusinessCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $actions = '', $nested = null)
    {
        $model = $this->findModel($id);

        $productCategoryIds = BusinessProductCategory::find()->where(['business_category_id' => $id])->all();
        $productCategoryIdsArray = array();
        foreach ($productCategoryIds as $value){
            $productCategoryIdsArray[] = $value->product_category_id;
        }

        $model->productCategoryIds = $productCategoryIdsArray;
        
        if($actions == 'deleteImg'){
                \Yii::$app->files->deleteFile($model, 'image');
                $model->image = '';
                $model->save();
        }

        if ($model->load(Yii::$app->request->post())) {
            
            \Yii::$app->files->upload($model, 'image');
             
            if($model->save()){
                //находим и удаляем все старые записи в BusinessProductCategory с id = $model->id
                $businessProdCat = BusinessProductCategory::find()->where(['business_category_id' => $model->id])->all();

                foreach ($businessProdCat as $value) {
                    $value->delete();
                }

                $model->productCategoryIds = $model->productCategoryIds == '' ? array() : $model->productCategoryIds;

                //Добавляем новые записи в таблицу BusinessProductCategory
                foreach ($model->productCategoryIds as $prod_cat_id) {
                    //Если существует такая категория продуктов, то дабавляем
                    $productCategory = ProductCategory::findOne((int)$prod_cat_id);
                    if ($productCategory) {
                        $businessProdCat = new BusinessProductCategory();
                        $businessProdCat->business_category_id = $model->id;
                        $businessProdCat->product_category_id = $prod_cat_id;

                        $businessProdCat->save();
                    }
                }
//                $cache = Yii::$app->cache;
//                $cache->delete($this->cacheKey); 
//                $cache->delete($this->cacheKeyBusinessCategoryRoots);
                Log::addAdminLog("business category[update]  ID: {$model->id}", $model->id, Log::TYPE_BUSINESS_CATEGORY);
                return $this->redirect(['view', 'id' => $model->id]);
            }   
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
        
    }
    
    public function actionUp($id)
    {
        $model = $this->findModel($id);
        
        $prev =  $model->prev()->one();
        if($prev){
            $model->insertBefore($prev);
            Yii::$app->session->setFlash('alert-success', 'Элемент перемещен вверх.');
        }
        else{
            Yii::$app->session->setFlash('alert-warning', 'Элемент находится на верхнем уровне.');
        }
        
        $this->refresh();
        return $this->redirect('index');
    }
    
    public function actionLeft($id)
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
        
        $this->refresh();
        return $this->redirect('index');
    }
    
    public function actionDown($id)
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
        
        $this->refresh();
        return $this->redirect('index');
    }
    
    public function actionRight($id)
    {
        $model = $this->findModel($id);
        
        $parent =  $model->prev()->one();
        if($parent){
            $model->appendTo($parent);
            Yii::$app->session->setFlash('alert-success', 'Элемент перемещен вправо.');
        }
        else{
            Yii::$app->session->setFlash('alert-warning', 'Элемент находится на верхнем уровне.');
        }
        
        if($model->isRoot()){
            $root = BusinessCategory::find()
                    ->where('id < :id', [':id' => $model->id])
                    ->andWhere(['depth' => 0])
                    ->orderBy('id DESC')
                    ->one();
            $model->appendTo($root);
            Yii::$app->session->setFlash('alert-success', 'Элемент перемещен вправо.');
        }
        
        $this->refresh();
        return $this->redirect('index');
    }

    /**
     * Deletes an existing BusinessCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        //находим и удаляем все старые записи в BusinessProductCategory с id = $model->id
        $businessProdCat = BusinessProductCategory::find()->where(['business_category_id' => $model->id])->all();
        foreach ($businessProdCat as $value) {
            $value->delete();
        }
        
        \Yii::$app->files->deleteFile($model, 'image');
        
        if (!$model->isRoot() && !$model->children(1)->all()) {
            $model->delete();
        }  else {
            $model->deleteWithChildren();
        }

        if ($model->errors) {
            Yii::$app->session->setFlash('alert-warning', array_values($model->errors)[0][0]);
        } else {
            Log::addAdminLog("business category[delete]  ID: {$id}", $id, Log::TYPE_BUSINESS_CATEGORY);
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
    /**
     * Merge the BusinessCategory model based on its primary key value.
     * @param integer $id
     * @return 
     * @throws NotFoundHttpException if the model cannot be found
     */    
//    public function actionMerge($id)            
//    {
//        $model = $this->findModel($id);
//        if (Yii::$app->request->post()) {
//            $post =  Yii::$app->request->post();
//            $idCategory = $post['BusinessCategory']['id'];
//          
//            foreach (Business::find()->where("\"idCategories\" && '{" . $id . "}'")->batch(100) as $modelbusiness){
//                foreach ($modelbusiness as $item){
//                    $idCategories = [];
//                    foreach ($item['idCategories'] as $idCategoryItem){
//                        if (($idCategoryItem!=$id) && ($idCategoryItem!=$idCategory)){
//                            $idCategories[] = $idCategoryItem;
//                        }
//                    }
//                    $idCategories[] = $idCategory;
//                    $idCategories ='{'.implode(',',$idCategories).'}';
//    //                Business::updateAll(['idCategories'=> $idCategories],['id'=>$item->id]);
//                    $item->idCategories = $idCategories;
//                    $item->save(false, ['idCategories']);
//                    unset($item);
//                }
//            }
//            
//            $isDelete = false;
//            if (!BusinessCategorySearch::find()->where(['pid'=>$model->id])->count()) {
//                $model->delete();
//                $isDelete = true;
//            }
//            
//            $cache = Yii::$app->cache;
//            $cache->delete($this->cacheKey); 
//            $cache->delete($this->cacheKeyBusinessCategoryRoots);
//            Log::addLog('business category[merge]', "ID: {$id} to {$idCategory}" . ($isDelete ? ". Delete ID: {$id}" : ''));
//            return $this->redirect(['view','id'=>$idCategory]);
//        }
//        return $this->redirect(['update','id'=>$id]);
//    }


    /**
     * Finds the BusinessCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BusinessCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BusinessCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    public function actionGetBusinessCategoryList(){
        $arr = [];
        if($id = Yii::$app->request->post('id')){
            if(is_string($id)){
                $temp = explode(',', $id);
                $id = end($temp);
            }
            $root = BusinessCategory::findOne(['id' => $id]);
            $model = BusinessCategory::find()->where(['pid' => $root->id])->orderBy('title')->all();
        }
        else{
            $model = BusinessCategory::find()->where(['pid'=>null])->orderBy('title')->all();
        }
        
        if($model){
            foreach ($model as $item){
                $arr[] = [
                    'id' => $item->id,
                    'text' => $item->title
                ];
            }  
        }
        else{
            $arr[] = ['id'=>$root->id, 'text'=>$root->title];
        }
        
        echo Json::encode($arr);
    }
}
