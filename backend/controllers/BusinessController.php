<?php

namespace backend\controllers;

use common\models\Log;
use common\models\BusinessCategoryCustomFieldLink;
use common\models\BusinessCustomField;
use common\models\BusinessCustomFieldValue;
use common\models\BusinessProductCategory;
use common\models\Invoice;
use common\models\ParseKarabas;
use common\models\ParseKino;
use common\models\ProductCategory;
use common\models\ProductCompany;
use common\models\ProductCategory as ProductCategoryModel;
use yii;
use common\models\Business;
use common\models\BusinessAddress;
use backend\models\search\Business as BusinessSearch;
use common\models\BusinessCategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use common\models\BusinessTime;
use common\models\Gallery;
use common\models\File;
use common\models\CountViews;
use common\models\City;
use common\models\Region;
use yii\helpers\Json;
use common\models\Afisha;
use common\models\ScheduleKino;
use common\models\Tag;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * BusinessController implements the CRUD actions for Business model.
 */
class BusinessController extends Controller
{
    public $cacheKeyCount = 'BusinessCategoryController_count_';
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
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

    public function actionCustomFieldForm()
    {
        $req = Yii::$app->request;

        if (!($post = $req->post()) or !$req->isAjax) {
            throw new HttpException(404);
        } elseif (!empty($post['cats']) and is_array($post['cats'])) {
            $custom_fields_id = BusinessCategoryCustomFieldLink::find()->select('custom_field_id')->where(['business_category_id' => $post['cats']]);
            $custom_fields = BusinessCustomField::find()->distinct()->where(['id' => $custom_fields_id])->all();

            $json = null;
            /** @var BusinessCustomField $field */
            foreach ($custom_fields as $field) {
                $data = [];
                foreach ($field->defaultValues as $value) {
                    $data[] = ['id' => $value->id, 'text' => $value->value];
                }
                $selected = null;
                if (!empty($post['business_id'])) {
                    $selected = $field->getIdOrValue((int)$post['business_id']);
                    $selected = $field->multiple ? $selected : array_shift($selected);
                }
                $json[] = [
                    'name' => $field->title,
                    'data' => $data,
                    'multiple' => $field->multiple,
                    'id' => $field->id,
                    'selected' => $selected,
                ];
            }
            return json_encode($json);
        } else {
            return '';
        }
    }

    /**
     * Lists all Business models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BusinessSearch;
        $queryParams = Yii::$app->request->getQueryParams();
        
        if ($post = Yii::$app->request->get('idDel')) {
            $arrList = explode(',', $post);
            foreach ($arrList as $id) {
                $this->actionDelete($id, true);
            }

            return $this->redirect(Yii::$app->request->referrer);
//            $dataProvider = $searchModel->search($queryParams);
//            return $this->renderAjax('index', [
//                'dataProvider' => $dataProvider,
//                'searchModel' => $searchModel
//            ]);
        }
       
        if (Yii::$app->request->isAjax and !empty($queryParams['idDel'])) {
            
            $this->deleteCache($queryParams['idDel']);
            $this->actionDelete($queryParams['idDel'], true);
  
            $dataProvider = $searchModel->search($queryParams);

            return $this->renderPartial('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel
            ]);
        }
        
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

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
        $getCookies = Yii::$app->getRequest()->getCookies();
        $model = new Business;
        if ($model->load(Yii::$app->request->post())) {
            if (empty($model->price_type)){
                $model->price_type = Business::PRICE_TYPE_FREE;
            }
            if ($model->save()) {
                if($model->type == $model::TYPE_KINOTHEATER){
                    $find_kino = ParseKino::find()->where(['local_cinema_id' => $model->id])->one();
                    if (isset($find_kino->local_cinema_id)) {
                        $find_kino->remote_cinema_id = $model->cinema_id;
                        $find_kino->save();
                    } else {
                        $parse_kino = new ParseKino();
                        $parse_kino->local_cinema_id = $model->id;
                        $parse_kino->remote_cinema_id = $model->cinema_id;
                        $parse_kino->save();
                    }
                }

                $this->updateParseKarabas($model);

                $this->saveTegs($model->tags);
                $this->saveTime($model->id);
                $this->addAddress($model->id);
                $this->checkRatio($model->id);
                $this->deleteCache($model->id);
                $this->saveCustomField($model->id);

                Log::addAdminLog("business[create]  ID: {$model->id}", $model->id, Log::TYPE_BUSINESS);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $selectCity = $getCookies->getValue('SUBDOMAIN');
        if ($selectCity) {
            /** @var City $city */
            $city = City::find()->where(['subdomain' => $selectCity])->one();
            if ($city) {
                $selectCity = $city->title . ($city->region ? (', ' . $city->region->title) : '');
            }
        }

        return $this->render('create', [
            'model' => $model,
            'address' => [],
            'selectCity' => $selectCity,
        ]);
    }

    /**
     * Обновляет ассоциацию или создает если нету для предприятия с сайта https://karabas.com/
     *
     * @param $model
     */
    private function updateParseKarabas($model){
        if (isset($model->karabas_business_id) && !empty($model->karabas_business_id)){
            $find_concert = ParseKarabas::find()->where(['local_business_id' => $model->id])->one();
            if (isset($find_concert->local_business_id)) {
                $find_concert->remote_business_id = $model->karabas_business_id;
                $find_concert->save();
            } else {
                $parse_karabas = new ParseKarabas();
                $parse_karabas->local_business_id = $model->id;
                $parse_karabas->remote_business_id = $model->karabas_business_id;
                $parse_karabas->save();
            }
        } elseif ($model->karabas_business_id == ''){
            $find_concert = ParseKarabas::find()->where(['local_business_id' => $model->id])->one();
            if ($find_concert){
                $find_concert->delete();
            }
        }
    }

    /**
     * Updates an existing Business model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $actions
     * @param string $name
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $actions = '', $name = '')
    {
        $getCookies = Yii::$app->request->cookies;
        $model = $this->findModel($id);
        $model->tags = explode(', ', $model->tags);
        $this->deleteCache($id);

        $parse_karabas = ParseKarabas::find()->where(['local_business_id' => $model->id])->one();
        if ($parse_karabas)
            $model->karabas_business_id = $parse_karabas->remote_business_id;

        $parse_kino = ParseKino::find()->where(['local_cinema_id' => $model->id])->one();
        if ($parse_kino)
            $model->cinema_id = $parse_kino->remote_cinema_id;

        $old_imag = $model->image;
        $old_background = $model->background_image;

        if ($actions == 'deleteImg') {
            Yii::$app->files->deleteAllFile($model, 'background_image', File::TYPE_BUSINESS);
            $model->image = '';
            if ($model->save()) {
                Log::addAdminLog("business[edit]  ID: {$model->id}", $model->id, Log::TYPE_BUSINESS);
            }

            return $this->redirect(['update','id' => $model->id]);
        } elseif ($actions == 'deleteBackgroundImg') {
            Yii::$app->files->deleteAllFile($model, 'background_image', File::TYPE_BUSINESS);
            $model->background_image = '';
            if ($model->save()) {
                Log::addAdminLog("business[edit]  ID: {$model->id}", $model->id, Log::TYPE_BUSINESS);
            }

            return $this->redirect(['update','id' => $model->id]);
        } elseif ($actions == 'deletePrice') {
            Yii::$app->files->deleteFilesGallery($model, 'price', [$name]);

            return $this->redirect(['update','id' => $model->id]);
        } elseif ($model->load(Yii::$app->request->post())) {

            if ($old_imag != ''){
                $model->image = $old_imag;
            }

            if ($old_imag != ''){
                $model->background_image = $old_background;
            }

            $this->saveTime($model->id);
            if ($model->save()) {
                if($model->type == $model::TYPE_KINOTHEATER){
                    $find_kino = ParseKino::find()->where(['local_cinema_id' => $model->id])->one();
                    if (isset($find_kino->local_cinema_id)) {
                        $find_kino->remote_cinema_id = $model->cinema_id;
                        $find_kino->save();
                    } else {
                        $parse_kino = new ParseKino();
                        $parse_kino->local_cinema_id = $model->id;
                        $parse_kino->remote_cinema_id = $model->cinema_id;
                        $parse_kino->save();
                    }
                }

                $this->updateParseKarabas($model);

                $this->saveTegs($model->tags);
                $this->addAddress($model->id);
                $this->checkRatio($model->id);
                $this->deleteCache($model->id);
                $this->saveCustomField($model->id);

                Log::addAdminLog("business[edit]  ID: {$model->id}", $model->id, Log::TYPE_BUSINESS);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        $address = [];
        if ($model->address) {
            $address = $model->address;
            foreach ($model->address as $key => $item) {
                //$address[$key]['oldAddress'] = $this->fixForMap($item->oldAddress);
                $address[$key]['phone'] = $this->fixForMap($item->phone);
                $address[$key]['working_time'] = $this->fixForMap($item->working_time);
            }
        }
        /** @var City $city */
        $city = $model->city;
        if (!$city and ($subdomain = $getCookies->getValue('SUBDOMAIN'))) {
            $city = City::find()->where(['subdomain' => $subdomain])->one();
        }
        $selectCity = $city ? ($city->title . ($city->region ? (', ' . $city->region->title) : '')) : '';

        return $this->render('update', [
            'model' => $model,
            'address' => $address,
            'message' => '',
            'selectCity' => $selectCity,
        ]);
    }
    
    public function actionUpdateGallery($idGallery, $idmodel)
    {
        if (!$idGallery) {
            return $this->redirect('/');
        }
        
        $model = Gallery::findOne(['id' => $idGallery]);
        
        if (!$model) {
            return $this->redirect('/');
        }
        
        if (Yii::$app->request->post('Gallery')) {
            $post = Yii::$app->request->post('Gallery');
            
            $model->updateAttributes(['title' => $post['title']]);
            return $this->redirect(['/business/update', 'id' => $idmodel]);
        }
        
        return $this->render('update-gallery', [
                'model' => $model,
            ]);
    }
    
    protected function fixForMap($str)
    {
        return str_replace(['"', "'", '\ ', '\t', chr(9)], ['\"', '\"', ', ', '', ''], trim($str, '\\'));
    }

    private function filterTime($i, $name)
    {
        return (isset(Yii::$app->request->post($name)[$i]) and ($str = substr(Yii::$app->request->post($name)[$i], 0, 5))) ?
            $str : '00:00';
    }

    private function saveTime($id)
    {
        $time = [];
        for ($i = 1; $i <= 7; $i++) {
            $time[$i] = [
                'start'         => $this->filterTime($i, 'start_time'),
                'end'           => $this->filterTime($i, 'end_time'),
                'break_start'   => $this->filterTime($i, 'break_start'),
                'break_end'     => $this->filterTime($i, 'break_end'),
            ];
        }
        $firstForFive = true;
        foreach ($time as $day => $times) {
            if ($times['start'] === $times['end']) {
                $times['start'] = '00:00';
                $times['end'] = '00:00';
                $times['break_start'] = '00:00';
                $times['break_end'] = '00:00';
            } else {
                if (($day >= 2) and ($day <= 5)) {
                    $firstForFive = false;
                }
                if ($times['break_start'] === $times['break_end']) {
                    $times['break_start'] = '00:00';
                    $times['break_end'] = '00:00';
                }
            }
        }
        if ($firstForFive) {
            for ($i = 2; $i <= 5; $i++) {
                $time[$i] = $time[1];
            }
        }
        BusinessTime::deleteAll(['idBusiness' => $id]);
        foreach ($time as $day => $times) {
            $businessTime = new BusinessTime();
            $businessTime->idBusiness = $id;
            $businessTime->weekDay = $day;
            $businessTime->start = $times['start'];
            $businessTime->end = $times['end'];
            $businessTime->break_end = $times['break_end'];
            $businessTime->break_start = $times['break_start'];
            $businessTime->save();
        }
    }
    
    protected function addAddress($id)
    {
        
        $add = Yii::$app->request->post('business_address');
        
        if(!$add){
            BusinessAddress::deleteAll(['idBusiness'=>$id]);
        }
        
        if(is_array($add)){
            
            $model = BusinessAddress::find()->select(['id'])->where(['idBusiness'=>$id])->orderBy('id ASC')->all();
            $listAddr = [];
            foreach ($model as $item){
                $listAddr[] = $item->id;
            }
            
            $newListAddr = [];
            foreach ($add as $item){
                if ($item['lat'] == ''){
                    continue;
                }

                if(!empty($item['id'])){
                    $newListAddr[] = (int)$item['id'];
                    $model_address = BusinessAddress::findOne(['id' => (int)$item['id']]);

                    $model_address->oldAddress = $item['address'];
                    $model_address->phone = $item['phone'];
                    $model_address->lat = $item['lat'];
                    $model_address->lon = $item['lon'];
                    $model_address->idBusiness = $id;
                    $model_address->working_time = $item['working_time'];
                    if (isset($item['city'])) {
                        $model_address->city = $item['city'];
                    }

                    $model_address->save();
                } else{
                    $model_address =  new BusinessAddress();

                    $model_address->street = $item['address'];
                    $model_address->phone = $item['phone'];
                    $model_address->lat = $item['lat'];
                    $model_address->lon = $item['lon'];
                    $model_address->idBusiness = $id;
                    $model_address->working_time = $item['working_time'];
                    if (isset($item['city'])) {
                        $model_address->city = $item['city'];
                    }
                    
                    $model_address->save();
                }
                //$item['address'] = str_replace('"',"/'", $item['address'] );
            }
            
            $listToDell = array_diff($listAddr, $newListAddr);
            if(is_array($listToDell) && !empty($listToDell)){
                BusinessAddress::deleteAll(['id' => $listToDell]);
            }
        }
    }

    /**
     * Deletes an existing Business model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param bool $isAjax
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id, $isAjax = false)
    {
        $model = $this->findModel($id);
        $afisha = Afisha::find()->where(':id = ANY("idsCompany")', ['id' => (int)$id])->all();
        if (!$isAjax and ($model->actions or $model->scheduleKino or $model->ads or $afisha)) {
            return $this->redirect(['check-links', 'id' => $id]);
        }
        $this->delGallerys($id);
        $this->delPrices($id);
        BusinessAddress::deleteAll(['idBusiness' => $id]);
        BusinessTime::deleteAll(['idBusiness' => $id]);
        CountViews::deleteAll(['pid' => $id, 'type' => File::TYPE_BUSINESS]);
        Invoice::deleteAll(['object_id' => $id]);
        $this->deleteCache($id);

        $find_kino_model = ParseKino::find()->where(['local_cinema_id' => $id])->one();
        if ($find_kino_model) {
            $find_kino_model->delete();
        }

        $parse_karabas = ParseKarabas::find()->where(['local_business_id' => $id])->one();
        if ($parse_karabas) {
            $parse_karabas->delete();
        }

        $model->delete();
        Log::addAdminLog("business[delete]  ID: {$id}", $id, Log::TYPE_BUSINESS);

        if (!$isAjax) {
            return $this->redirect(['index']);
        }
        return null;
    }

    public function deleteCache($id)
    {
        $model = Business::findOne($id);
        if ($model) {
            $cache = Yii::$app->cache;
            foreach ($model->idCategories as $item){
                $cache->delete($this->cacheKeyCount . $item);
            }
        }
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
        if (($model = Business::findOne($id)) !== null) {
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

    private function getWhereLike($searchText)
    {
        return ['~~*', 'title', ('%' . $searchText . '%')];
    }

    public function actionCategoryList()
    {
        $pid = null;
        $where = [];
        if (Yii::$app->request->post("pid")) {
            $pid = Yii::$app->request->post("pid");
        }
        if($searchText = Yii::$app->request->post("search")){
            $where = $this->getWhereLike($searchText);
        }

        if(!$pid){
            $categorylist = BusinessCategory::find()->select(['id','title'])->roots()->andWhere($where)->orderBy('title')->all();
        }
        else{
            $category = BusinessCategory::find()->where(['id' => $pid])->one();
            $categorylist = $category->children(1)->andWhere($where)->orderBy('title')->all();
        }
        $parent = 0;
        if (($pid = Yii::$app->request->post("pid")) and $root = BusinessCategory::findOne(['id' => $pid])) {
            $parent = $root->parents(1)->one();
            $parent = isset($parent->id) ? $parent->id : $parent;
        }
        echo Json::encode(['items' => $categorylist, 'back_id' => (int)$parent]);
    }

    public function actionCityList()
    {
        $pid = Yii::$app->request->post('pid', null);
        $old = Yii::$app->request->post('old', null);
        $search = Yii::$app->request->post('search', null);

        $sel = ['id','title'];
        $searchWhere = ($search) ? $this->getWhereLike($search) : [];

        //области - pid: '', old: int, search: null
        // поиск - pid: '', old: '', search: string
        //города - pid: int, old: '', search: null
        // поиск - pid: int, old: int, search: string
        //выбор города - pid: int, old: int
        if (!$pid) {
            $categoryList = Region::find()->select($sel)->where($searchWhere)->orderBy($sel[1])->all();
        } elseif (!$old or !is_null($search)) {
            $categoryList = City::find()->select($sel)->where(['idRegion' => $pid])->andWhere($searchWhere)->orderBy($sel[1])->all();
        } else {
            $categoryList = [];
        }
        echo Json::encode($categoryList);
    }

    public function actionCheckCompany()
    {
        $arr = [
            'company' => 'Не найдена',
            'city' => '',
        ];
        $id = $_POST['id'];
        if (is_numeric($id)) {
            $model = Business::findOne((int)$id);
        } else {
            $model = null;
        }
        
        if($model){
            $arr = [
                'company' => $model->title,
                'city' => $model->city->title,
            ];
        }
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    public function actionCheckCompanys()
    {
        $arr = [];
        $id = $_POST['id'];
        
        $id = trim($id, ',');
        
        $ids = explode(',', $id);
        
        foreach ($ids as $item){
            if (is_numeric($item)) {
                $model = Business::findOne((int)$item);
            } else {
                $model = null;
            }
            
            if($model){
                $arr[] = [
                    'id' => $model->id,
                    'class' => '',
                    'title' => $model->title,
                    'cityTitle' => $model->city->title,
                ];
            }
            else{
                $arr[] = [
                    'id' => (int)$item,
                    'class' => 'id-error',
                    'title' => 'Не найденно',
                    'cityTitle' => '',
                ];
            }
        }
        
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    private function checkRatio($id){
        $arr = ['description', 'site', 'phone', 'urlVK', 'urlFB', 'urlTwitter', 'email', 'shortDescription', 'skype', 'image'];
        
        $model = $this->findModel($id);
        
        $ratio = 0;
        
        foreach ($arr as $attr){
            if($model->$attr){
                $ratio += ($attr == 'image')? 2 : 1;
            } 
        }
        
        $price = File::find()->where(['pid' => $model->id, 'type' => File::TYPE_BUSINESS_PRICE])->one();
        if($price){
            $ratio += 1;
        }
        
        if($model->address){
            $ratio += 3;
        }

        if($model->actions){
            $ratio += 3;
        }

        if($model->type == Business::TYPE_KINOTHEATER){
            $m = ScheduleKino::find()->where(['idCompany' => $model->id])->one();
            if($m){
                $ratio += 3;
            }
        }

        $m = Afisha::find()->where("\"idsCompany\" && '{".$model->id."}'")->one();
        if($m){
            $ratio += 3;
        }

        $gal = Gallery::find()->where(['pid' => $model->id, 'type' => File::TYPE_BUSINESS])->all();
        foreach ($gal as $item){
            $ratio += 1;
        }

        $model->ratio = ($ratio)? $ratio : 1;
        
//        $model->save(false, ['ratio']);
        $model->updateAttributes(['ratio' => $model->ratio]);
    }

    private function saveTegs($tegs){
        $arr = explode(', ', $tegs);
        
        foreach ($arr as $item){
            $model = Tag::find()->where(['like', 'title', $item])->one();
            
            if(!$model){
                $model = new Tag();
            }
            
            $model->title = $item;
            $model->save();
        }
    }

    public function actionAjaxSearch($q = null, $id = null)
    {
        if (!Yii::$app->request->isAjax) throw new HttpException(403);
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {

            $business = Business::find()->where($this->getWhereLikeTitle($q));

            if($city = Yii::$app->request->get('city')){
                $business = $business->andWhere(['idCity' => $city]);
            }
            $business = $business->select(['id', 'title'])->limit(20)->all();
            $business = ArrayHelper::map($business, 'id', 'title');
            $out['results'] = [];
            foreach ($business as $id => $name) $out['results'][] = ['id' => $id, 'text' => $name];
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Business::findOne($id)->title];
        }
        return $out;
    }

    private function getWhereLikeTitle($searchText)
    {
        $lower = '%' . mb_strtolower($searchText,  'UTF-8') . '%';
        $where = ['~~*', 'title', $lower];
        return $where;
    }

    private function saveCustomField($id)
    {
        $custom_fields = Yii::$app->request->post('customfield');
        BusinessCustomFieldValue::deleteAll(['business_id' => $id]);

        if (is_array($custom_fields)) {
            foreach ($custom_fields as $field => $values) {
                $attr = BusinessCustomField::findOne((int)$field);
                if (empty($attr)) {
                    continue;
                }
                if (!is_array($values)) {
                    $values = [(string)$values];
                }
                foreach ($values as $value) {
                    $attr->addNewValue($id, $value);
                }
            }
        }
    }

    public function actionCheckLinks($id)
    {
        $business = Business::findOne((int)$id);

        if (!$business) {
            throw new HttpException(404);
        }

        $afisha = Afisha::find()->where(':id = ANY("idsCompany")', ['id' => (int)$id])->all();

        return $this->render('check-links', ['business' => $business, 'afisha' => $afisha]);
    }

    public function actionDeleteWithLinks($id)
    {
        $model = $this->findModel($id);

        foreach ($model->actions as $item) {
            $item->delete();
        }
        foreach ($model->ads as $item) {
            $item->delete();
        }
        foreach ($model->scheduleKino as $item) {
            $item->delete();
        }
        $afisha = Afisha::find()->where(':id = ANY("idsCompany")', ['id' => (int)$id])->all();

        foreach ($afisha as $item) {
            $item->delete();
        }
        $this->delGallerys($id);
        $this->delPrices($id);
        BusinessAddress::deleteAll(['idBusiness' => $id]);
        BusinessTime::deleteAll(['idBusiness' => $id]);
        Invoice::deleteAll(['object_id' => $id]);
        CountViews::deleteAll(['pid' => $id, 'type' => File::TYPE_BUSINESS]);
        $this->deleteCache($id);

        $model->delete();
        Log::addAdminLog("business[delete]  ID: {$id}", $id, Log::TYPE_BUSINESS);
//
//        return $this->redirect(['index']);
    }
    
    public function actionDeleteList()
    {
        if(Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $array = $data['idDel'];
            if (is_array($array) and !empty($array)) {
                foreach ($array as $id) {
                    $this->actionDeleteWithLinks((int)$id);
                }
            }
        }
    }

    /**
     * Получаем список категорий продуктов по ид предприятия
     * либо все если ид не установлен
     *
     */
    public function actionBusinessCategoryByBusiness()
    {
        $out = [];
        $selected = '';
        if (isset($_POST['depdrop_parents'])) {
            $parent = $_POST['depdrop_parents'][0];
            if (!empty($parent)) {
                $business_product_category = BusinessProductCategory::find()
                    ->select('*')
                    ->where(['business_category_id' => $_POST['depdrop_parents'][0]])
                    ->all();

                #Преобразуем к виду [['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],[...],...]
                foreach ($business_product_category as $value) {
                    $product_categorys = ProductCategory::find()->where(['id' => $value->product_category->id])->orderBy('title ASC')->batch(100);
                    foreach ($product_categorys as $b) {
                        foreach ($b as $item) {
                            $out[] = ['id' => $item->id, 'name' => $item->title];
                            foreach ($item->children()->addOrderBy('title')->all() as $ch) {
                                $pref = ' ';
                                for ($i = 1; $i <= $ch->depth; $i++) {
                                    $pref .= '-';
                                }
                                $out[] = ['id' => $ch->id, 'name' => $pref . $ch->title];
                            }
                        }
                    }

                    $selected = $out[0]['id'];
                }

                if (!$business_product_category){
                    $selected = '';
                    $out[] = null;
                }
            }
        } else {
            foreach (ProductCategory::find()->roots()->orderBy('title ASC')->batch(100) as $b) {
                foreach ($b as $item) {
                    $out[] = ['id' => $item->id, 'name' => $item->title];
                    foreach ($item->children()->addOrderBy('title')->all() as $ch) {
                        $pref = ' ';
                        for ($i = 1; $i <= $ch->depth; $i++) {
                            $pref .= '-';
                        }
                        $out[] = ['id' => $ch->id, 'name' => $pref . $ch->title];
                    }
                }
            }
        }
        echo Json::encode(['output' => $out, 'selected' => (string)$selected]);
    }

    public function actionBusinessByUser(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $user_id = $parents[0];

                /** @var Business[] $businesses */
                $businesses = Business::find()->where(['idUser' => (int)$user_id])->all();
                foreach ($businesses as $business) {
                    $out[] = ['id' => $business->id, 'name' => $business->title];
                }
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionCityByBusiness()
    {
        $out = [];
        $selected = '';
        if (isset($_POST['depdrop_parents'])) {
            $parent = $_POST['depdrop_parents'][0];
            if (!empty($parent)) {
                #Предприятие по id
                $business = Business::find()->where(['id' => $parent])->one();

                #Город по id
                $cities = City::find()->where(['id' => $business['idCity']])->select(['id', 'title'])->all();
                $cities = ArrayHelper::map($cities, 'id', 'title');

                #Преобразуем к виду [['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],[...],...]
                foreach ($cities as $id => $name) $out[] = ['id' => $id, 'name' => $name];
                $selected = $out[0]['id'];
            } else {
                $cities = City::find()->where(['main' => City::ACTIVE])->select(['id', 'title'])->all();
                $cities = ArrayHelper::map($cities, 'id', 'title');
                foreach ($cities as $id => $name) $out[] = ['id' => $id, 'name' => $name];
            }
        } else {
            $cities = City::find()->where(['main' => City::ACTIVE])->select(['id', 'title'])->all();
            $cities = ArrayHelper::map($cities, 'id', 'title');
            $out[] = ['id' => count($cities), 'name' => count($cities)];
            foreach ($cities as $id => $name) $out[] = ['id' => $id, 'name' => $name];
        }
        echo Json::encode(['output' => $out, 'selected' => (string)$selected]);
    }

    public function actionGetPhone(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $businessId = $data['businessId'];

            $business = Business::findOne((int)$businessId);

            if ($business){
                $phone = $business->phone ? $business->phone : '';
            } else {
                $phone = '';
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['phone' => $phone];
        }
    }

    public function actionGetAllProductCategory(){
        $out = [];
        $listProdCat = ProductCompany::find()
            ->leftJoin('ProductCategoryCategory','"ProductCategoryCategory"."ProductCompany" = product_company.id')
            ->select(['"product_company"."id"', '"product_company"."title"'])
            ->limit(100)
            ->all();

        if (isset($_POST['depdrop_parents'])) {
            $idCategory = $_POST['depdrop_parents'][0];
            if (!empty($idCategory)) {
                $rootcategory = ProductCategoryModel::findOne(['id'=>(int)$idCategory]);
                if($rootcategory){
                    $idCategory = (!$rootcategory->isRoot()) ? $rootcategory->parents()->one()->id : $idCategory;
                    $listProdCat = ProductCompany::find()
                        ->leftJoin('ProductCategoryCategory','"ProductCategoryCategory"."ProductCompany" = product_company.id')
                        ->where(['ProductCategoryCategory.ProductCategory' => (int)$idCategory])
                        ->select(['"product_company"."id"', '"product_company"."title"'])
                        ->all();
                }
            }
        }

        foreach ($listProdCat as $value) {
            $out[] = ['id' => $value->id, 'name' => $value->title];
        }
        $selected = '';

        return Json::encode(['output' => $out, 'selected' => (string)$selected]);
    }
}
