<?php

namespace m\controllers;

use Yii;
use common\models\Business;
use common\models\BusinessAddress;
use common\models\search\Business as BusinessSearch;
use common\models\BusinessCategory;
use common\models\search\ProductCategory;
use common\models\City;
use common\models\Region;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\BusinessTime;
use common\models\Gallery;
use common\models\File;
use common\models\CountViews;
use yii\widgets\Breadcrumbs;
use yii\helpers\Json;

/**
 * BusinessController implements the CRUD actions for Business model.
 */
class BusinessController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    public function actions()
    {
        return [
            'deleteGallery' => [
                'class' => 'common\extensions\fileUploadWidget\galleryActions\DeleteGallery',
                'view' => 'update',
            ],
            'addGallery' => [
                'class' => 'common\extensions\fileUploadWidget\galleryActions\AddGallery',
                'view' => 'update',
            ],
            'uploadGallery' => [
                'class' => 'common\extensions\fileUploadWidget\galleryActions\UploadGallery',
                'view' => 'update',
            ],
        ];
    }
    /**
     * Lists all Business models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BusinessSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),true);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Business model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Business model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {     
        if(Yii::$app->request->post('isUpdate')){
            $id = Yii::$app->request->post('id');
            return $this->actionUpdate($id);
        }
        
        $model = new Business;

        if ($model->load(Yii::$app->request->post())) {

            \Yii::$app->files->upload($model, 'image');

            $model->idCity = 1427;
            $model->idUser = 2;
            if($model->save()){
                $this->addTime($model->id);
                $this->addAddress($model->id);
                return $this->render('view', ['model' => $this->findModel($model->id)]);
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
                'address' => [],
            ]);
        }
    }
    
    public function actionResetImage(){
        if($id = Yii::$app->request->post('id')){
            $model = Business::findOne(['id' => $id]);
            if(!empty($model->image)){
                Yii::$app->files->deleteFile($model, 'image');
                $model->image = '';
                $model->save(false, ['image']);
            } 
        }
    }
    
    public function actionModal(){
        return $this->renderAjax('modal', [
                ]);
    }

    /**
     * Updates an existing Business model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {      
        $model = $this->findModel($id);
        
        $adres = BusinessAddress::findOne(['idBusiness' => $model->id]);
        
        if ($model->load(Yii::$app->request->post())) {
            
            \Yii::$app->files->upload($model, 'image');
            
            $this->addTime($model->id);
            
            if($model->save()){
                $this->addAddress($model->id);
                return $this->render('view', ['model' => $this->findModel($model->id)]);
            }
            
        } 
                       
        return $this->render('update', [
            'model' => $model,
            'message'=>"",
            'adres'=> $adres
        ]);
        
    }
    
    protected function fixForMap($str){
        return str_replace(['"', "'", '\ ', '\t', chr(9)], ['\"', '\"', ', ', '', ''], $str);
    }
    
    protected function addTime($id) {
        $start_time = Yii::$app->request->post('start_time');
        $end_time = Yii::$app->request->post('end_time');

        if(Yii::$app->request->post('business_time_full_week')){

            $start_time[6] = $start_time[1];
            $end_time[6] = $end_time[1];
            $start_time[7] = $start_time[1];
            $end_time[7] = $end_time[1];
        }
        
        $allWeek = true;
        
        foreach ($start_time as $key=>$value){
            if($key>1 && $key<6 && $value != '00:00' ){
                $allWeek = false;
            } 
        }
        
        $tm_s = $start_time[1];
        $tm_e = $end_time[1];
//        echo \yii\helpers\BaseVarDumper::dump($start_time, 10, true);die();
        $businessTime = new BusinessTime();
        $businessTime->deleteAll(['idBusiness'=>$id]);   
        
        foreach ($start_time as $key=>$value){
            
            if($allWeek && $value == '00:00' && $key > 1 && $key < 6){
                $value = $tm_s;
                $end_time[$key] = $tm_e;
            }
            
            if($value != '00:00'){
                $businessTime = new BusinessTime();
                $businessTime->idBusiness = $id;
                $businessTime->weekDay = $key;
                $businessTime->start = $value;
                $businessTime->end = $end_time[$key];
                $businessTime->save();
            }
                
            
        }
        //echo \yii\helpers\BaseVarDumper::dump($start_time, 10, true);
        //echo \yii\helpers\BaseVarDumper::dump($end_time, 10, true); die('bbb');
    }
    
    protected function addAddress($id) {
        $modeladress =  new BusinessAddress;  
        $modeladress->deleteAll(['idBusiness'=>$id]);   
        $add = Yii::$app->request->post('business_address');
        if(is_array($add)){
            foreach ($add as $item){
                $modeladress =  new BusinessAddress;  
                $modeladress->address = $item['address'];
                $modeladress->phone = $item['phone'];
                $modeladress->lat = $item['lat'];
                $modeladress->lon = $item['lon'];
                $modeladress->idBusiness = $id;
                $modeladress->save();
        }
        }
    }
    /**
     * Deletes an existing Business model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        \Yii::$app->files->deleteFile($this->findModel($id), 'image');
        $this->delGallerys($id);
        $this->delPrices($id);
        BusinessAddress::deleteAll(['idBusiness' => $id]);
        BusinessTime::deleteAll(['idBusiness' => $id]);
        CountViews::deleteAll(['pid'=>$id, 'type'=>File::TYPE_BUSINESS]);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Business model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Business the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if ($model = Business::find()->where(['id' => $id])->one()) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function delGallerys($id)
    {
        $models = Gallery::find()->where(['type' => File::TYPE_BUSINESS, 'pid' => $id])->all();
  
        if($models){
            foreach ($models as $gal){
                
                $files = File::find()->where(['type' => File::TYPE_GALLERY, 'pid' => $gal->id])->all();
                
                if (!empty($files)) {

                    $listFiles = [];
                    foreach ($files as $item) {

                        $listFiles[] = $item->name;

                        $item->delete();
                    }

        \Yii::$app->files->deleteFilesGallery($gal, 'attachments', $listFiles, null, $this->id . '/' . $gal->id);
                }
               $gal->delete();
            }
        }  
    }
    
    protected function delPrices($id)
    {
        $models = File::find()->where(['type' => File::TYPE_BUSINESS_PRICE, 'pid' => $id])->all();
        
        if($models){
            foreach ($models as $item){
                \Yii::$app->files->deleteFilesGallery(new Business(), 'price', [$item->name]);
            }
        }
    }
    
    public function actionUserBusiness()
    {
        $out=[];
        if (isset($_POST['depdrop_parents']))
        {
            $id = end($_POST['depdrop_parents']);
            if ($id ==\common\models\Ticket::TYPE_COMPANY){ 
                $list = Business::find()->where(['idUser' => 10])->orderBy('title')->asArray()->all();
                $selected = '';
                if ($list) {
                    foreach ($list as $i => $item) {
                        $out[] = ['id' => $item['id'], 'name' => $item['title']];
                        if ($i == 0) {
                            $selected = $item['id'];
                        }
                    }
                }
            }    
        }  
//        if(count($out)==0){
            $out[] = [];
            $selected = 0;
//        }
        echo Json::encode(['output' => $out, 'selected' => $selected]);
                    return;
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

    public function actionCategoryList()
    {
        $pid=NULL;
        if (YII::$app->request->post("pid")) {
            $pid = YII::$app->request->post("pid");
        }
        $where = [];
        if($searchText = YII::$app->request->post("search")){
            $where = $this->getWhereLike($searchText);
        }
        $categorylist = BusinessCategory::find()->select(['id','title'])->where(['pid'=>$pid])->andWhere($where)->orderBy('title')->asArray()->all();
        echo Json::encode($categorylist);
    }
    
    public function actionProductCategoryList()
    {
        $pid=NULL;
        
        $where = [];
        if($searchText = YII::$app->request->post("search")){
            $where = $this->getWhereLike($searchText);
        }
            
        if (YII::$app->request->post("pid")) {
            $pid = YII::$app->request->post("pid");
            $root = ProductCategory::findOne(['id' => $pid]);
            $categorylist = $root->children(1)->select(['id','title'])->andWhere($where)->orderBy('title')->all();
        }
        else{
           $categorylist = ProductCategory::find()->where($where)->roots()->orderBy('title')->asArray()->all();
        }
        echo Json::encode($categorylist);
    }
    
    public function actionCityList()
    {
        $pid=NULL;
        $categorylist =[]; 
        $where = [];
        if($searchText = YII::$app->request->post("search")){
            $where = $this->getWhereLike($searchText);
        }
        if (YII::$app->request->post("pid")) {
            $pid = YII::$app->request->post("pid");
            $old = YII::$app->request->post("old");
            if(empty($old) || $searchText || ($searchText == '')) {
                $categorylist = City::find()->select(['id','title'])->where(['idRegion'=>$pid])->andWhere($where)->orderBy('title')->asArray()->all();
            }   
        }
        else{
           $categorylist = Region::find()->select(['id','title'])->where($where)->orderBy('title')->asArray()->all();
        }
        echo Json::encode($categorylist);
    }
    
}
