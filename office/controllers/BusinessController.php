<?php

namespace office\controllers;

use common\models\Afisha;
use common\models\BusinessProductCategory;
use common\models\Invoice;
use common\models\ProductCompany;
use common\models\QuestionConversation;
use common\models\ScheduleKino;
use common\models\Ticket;
use common\models\UserPaymentTypeBusiness;
use common\models\Log;
use yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use common\models\Business;
use common\models\BusinessAddress;
use common\models\BusinessCategory;
use common\models\BusinessTime;
use common\models\City;
use common\models\CountViews;
use common\models\File;
use common\models\Gallery;
use common\models\Region;
use common\models\ProductCategory as ProductCategoryModel;
use common\models\search\ProductCategory;
use office\models\search\Business as BusinessSearch;

/**
 * BusinessController implements the CRUD actions for Business model.
 */
class BusinessController extends DefaultController
{
    const TAX = 0;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors ['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
            ],
        ];
        return $behaviors;
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['post'],
//                ],
//            ],
//        ];
    }

    public function actions()
    {
        return [
            'deleteGallery' => [
                'class' => 'common\extensions\fileUploadWidget\officeGalleryActions\DeleteGallery',
                'view' => 'update',
            ],
            'addGallery' => [
                'class' => 'common\extensions\fileUploadWidget\officeGalleryActions\AddGallery',
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
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->pagination->pageSize= 10;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Business model.
     * @param integer $id
     * @param null $message
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id, $message = null)
    {
        $model = $this->findModel($id);
        $conversations = QuestionConversation::find()->where(['object_type' => File::TYPE_BUSINESS, 'object_id' => $model->id])->all();

        return $this->render('view', [
            'message' => $message,
            'model' => $this->findModel($id),
            'conversations' => $conversations,
        ]);
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
        if (($model = Business::find()->where(['id' => $id, 'idUser' => Yii::$app->user->identity->id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
            return $this->redirect(['/business/add-gallery', 'id' => $idmodel]);
        }

        $business = Business::findOne($idmodel);

        return $this->render('update-gallery', [
            'model' => $model,
            'business' => $business,
        ]);
    }

    /**
     * Creates a new Business model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param null $tariff
     * @return mixed
     */
    public function actionCreate($tariff = null, $businessType = null)
    {
        if (!($tariff = (int)$tariff)) {
            return $this->redirect(['/business/pay']);
        }

        if (!($businessType = (int)$businessType)) {
            return $this->redirect(['/business/business-type', 'tariff' => $tariff]);
        }

        $model = new Business();
        $model->price_type = $tariff;
        $model->type = $businessType;

        if ($model->load(Yii::$app->request->post())) {
            $model->price_type = $tariff;
            $model->isChecked = 1;
            $model->due_date = date('Y-m-d H:i:s', time() + 3600 * 24 * 7);
            if ($model->save()) {
                Log::addUserLog("business[create]  ID: {$model->id}", $model->id, Log::TYPE_BUSINESS);

                $business = Business::findOne($model->id);
                $tariffs = $business->getTariffs(Yii::$app->user->identity);
                $order = $tariffs[$business->price_type]['order'];

                $invoice = new Invoice();
                if ($model->price_type === Business::PRICE_TYPE_FREE){
                    //$invoice->paid_status = Invoice::PAID_YES;
                } else {
                    $invoice->paid_status = Invoice::PAID_NO;
                    $invoice->user_id = Yii::$app->user->id;
                    $invoice->object_type = File::TYPE_BUSINESS;
                    $invoice->object_id = $model->id;
                    $invoice->amount = $order->amount;
                    $invoice->paid_from = date('Y-m-d H:i:s');
                    $invoice->description = $order->description;
                    $invoice->order_id = time() . "_invoices_{$model->id}_own_1_from_{$model->idUser}";

                    $invoice->save();
                }

                $this->addPaymentTypes($model->id, $model->user_payments);
                $this->addTime($model->id);
                $this->addAddress($model->id);
                $this->checkRatio($model->id);

                if ($tariff !== Business::PRICE_TYPE_FREE) {
                    return $this->redirect(['/transactions/view', 'id' => $invoice->id]);
                } else {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', ['model' => $model, 'address' => []]);
    }

    public function actionBusinessType($tariff = null)
    {
        return $this->render('business-type', ['tariff' => $tariff]);
    }

    protected function addTime($id)
    {
        $start_time = Yii::$app->request->post('start_time');
        $end_time = Yii::$app->request->post('end_time');

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

    protected function addAddress($id)
    {

        $add = Yii::$app->request->post('business_address');

        if(!$add){
            BusinessAddress::deleteAll(['idBusiness'=>$id]);
        }

        if(is_array($add)){

            /** @var BusinessAddress[] $models */
            $models = BusinessAddress::find()->select(['id'])->where(['idBusiness'=>$id])->orderBy('id ASC')->all();
            $listAddr = [];
            foreach ($models as $model){
                $listAddr[] = $model->id;
            }

            $newListAddr = [];
            foreach ($add as $item){
                if ($item['lat'] == ''){
                    continue;
                }

                if(!empty($item['id'])){
                    $newListAddr[] = (int)$item['id'];
                    $model_address = BusinessAddress::findOne(['id' => (int)$item['id']]);

                    $model_address->street = $item['address'];
                    $model_address->phone = $item['phone'];
                    $model_address->lat = $item['lat'];
                    $model_address->lon = $item['lon'];
                    $model_address->idBusiness = $id;
                    if (isset($item['city'])) {
                        $model_address->city = $item['city'];
                    }

                    $model_address->save();
                } else{
                    $model_address =  new BusinessAddress();

                    //$model_address->oldAddress = $item['address'];
                    $model_address->street = $item['address'];
                    $model_address->phone = $item['phone'];
                    $model_address->lat = $item['lat'];
                    $model_address->lon = $item['lon'];
                    $model_address->idBusiness = $id;
                    if (isset($item['city'])) {
                        $model_address->city = $item['city'];
                    }
                    $model_address->save();
                }
            }

            $listToDell = array_diff($listAddr, $newListAddr);
            if(is_array($listToDell) && !empty($listToDell)){
                BusinessAddress::deleteAll(['id' => $listToDell]);
            }
        }
    }

    protected function addPaymentTypes($id, $types)
    {
        if (!$types) {
            UserPaymentTypeBusiness::deleteAll(['business_id' => $id]);
        } else {
            /** @var UserPaymentTypeBusiness[] $existTypes */
            $existTypes = UserPaymentTypeBusiness::find()->where(['business_id' => $id])->all();

            foreach ($existTypes as &$existType) {
                if ($key = array_search($existType->user_payment_type_id, $types)) {
                    unset($types[$key]);
                    unset($existType);
                } else {
                    $existType->delete();
                    unset($existType);
                }
            }

            foreach ($types as $type) {
                $newType = new UserPaymentTypeBusiness();
                $newType->business_id = $id;
                $newType->user_payment_type_id = $type;
                $newType->save();
            }
        }
    }

    /**
     * Updates an existing Business model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $actions
     * @param string $name
     * @param null $tariff
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $actions = '', $name = '', $tariff = null)
    {
        $model = $this->findModel($id);
        if ($tariff) {
            $tariff = (int)$tariff;
            $model->price_type = $tariff;
        }

        $old_image = $model->image;
        $old_background = $model->background_image;

        if ($actions === 'deleteImg') {
            Yii::$app->files->deleteAllFile($model, 'background_image', File::TYPE_BUSINESS);
            $model->image = '';
            if ($model->save()) {
                Log::addUserLog("business[update]  ID: {$model->id}", $model->id, Log::TYPE_BUSINESS);
            }

            return $this->redirect(['update','id' => $model->id]);
        } elseif ($actions == 'deleteBackgroundImg') {
            Yii::$app->files->deleteAllFile($model, 'background_image', File::TYPE_BUSINESS);
            $model->background_image = '';
            if ($model->save()) {
                Log::addUserLog("business[update]  ID: {$model->id}", $model->id, Log::TYPE_BUSINESS);
            }


            return $this->redirect(['update','id' => $model->id]);
        } elseif ($actions === 'deletePrice') {
            Yii::$app->files->deleteFilesGallery($model, 'price', [$name]);

            return $this->redirect(['index']);
        } elseif ($model->load(Yii::$app->request->post())) {
            $this->addTime($model->id);
            if ($tariff) {
                $model->price_type = $tariff;
            }

            if ($old_image) {
                $model->image = $old_image;
            }

            if ($old_image) {
                $model->background_image = $old_background;
            }

            $model->isChecked = 1;
            if ($model->save()) {
                $this->addPaymentTypes($model->id, $model->user_payments);
                $this->addAddress($model->id);
                $this->checkRatio($model->id);
                Log::addUserLog("business[update]  ID: {$model->id}", $model->id, Log::TYPE_BUSINESS);

                if ($tariff and ($tariff !== Business::PRICE_TYPE_FREE)) {
                    return $this->redirect(['/business/invoice', 'id' => $model->id]);
                } else {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }

        }
        if ($model->address) {
            $address = $model->address;
            foreach ($model->address as $key=>$item) {
                //$address[$key]['address'] = $this->fixForMap($item->address);
                $address[$key]['phone'] = $this->fixForMap($item->phone);
            }
        }

        $model->user_payments = UserPaymentTypeBusiness::find()
            ->joinWith('userPaymentType')
            ->select('user_payment_type.id')
            ->where(['business_id' => $model->id, 'user_payment_type.user_id' => Yii::$app->user->id])
            ->column();

        return $this->render('update', [
            'model' => $model,
            'address' => !empty($address) ? $address : [],
            'message' => '',
        ]);
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

        $model->updateAttributes(['ratio' => $model->ratio]);
    }

    public function actionAddGallery($id){
        $model = $this->findModel($id);

        return $this->render('add_gallery', [
            'model' => $model,
        ]);
    }

    public function actionInvoice($id)
    {
        $id = (int)$id;
        $business = Business::findOne($id);

        if (!$business or !Yii::$app->user->identity) {
            throw new HttpException(404);
        }

        $invoices = Invoice::find()->where([
            'object_type' => File::TYPE_BUSINESS,
            'user_id' => Yii::$app->user->id,
            'object_id' => $id,
        ])->orderBy(['created_at' => SORT_DESC])->all();

        return $this->render('invoice', [
            'invoices' => $invoices,
            'business' => $business,
        ]);
    }

    protected function fixForMap($str){
        return str_replace(['"', "'", '\ ', '\t', chr(9)], ['\"', '\"', ', ', '', ''], trim($str, '\\'));
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
        $model = $this->findModel($id);
        $model->delete();
        Log::addUserLog("business[delete]  ID: {$id}", $id, Log::TYPE_BUSINESS);

        return $this->redirect(['index']);
    }

    protected function delGallerys($id)
    {
        /** @var Gallery[] $models */
        $models = Gallery::find()->where(['type' => File::TYPE_BUSINESS, 'pid' => $id])->all();
  
        if($models){
            foreach ($models as $gal){
                /** @var File[] $files */
                $files = File::find()->where(['type' => File::TYPE_GALLERY, 'pid' => $gal->id])->all();
                
                if (!empty($files)) {

                    $listFiles = [];
                    foreach ($files as $item) {
                        $listFiles[] = $item->name;
                        $item->delete();
                    }
                    Yii::$app->files->deleteFilesGallery($gal, 'attachments', $listFiles, null, $this->id . '/' . $gal->id);
                }
                $gal->delete();
            }
        }  
    }

    protected function delPrices($id)
    {
        /** @var File[] $models */
        $models = File::find()->where(['type' => File::TYPE_BUSINESS_PRICE, 'pid' => $id])->all();
        
        if($models){
            foreach ($models as $item){
                \Yii::$app->files->deleteFilesGallery(new Business(), 'price', [$item->name]);
            }
        }
    }

    public function actionUserBusiness()
    {
        $out = [];
        $selected = 0;
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            if ($id == Ticket::TYPE_COMPANY){
                $list = Business::find()->where(['idUser' => 10])->orderBy('title')->asArray()->all();
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
        echo Json::encode(['output' => $out, 'selected' => (string)$selected]);
        return;
    }

    public function actionCategoryListOffice()
    {
        $pid = Yii::$app->request->post('pid');

        $where = [];
        if ($searchText = Yii::$app->request->post('search')) {
            $where = $this->getWhereLike($searchText);
        }

        if (!$pid) {
            $categoryList = BusinessCategory::find()->select(['id','title'])->roots()->andWhere(['sitemap_en' => 1])->andWhere($where)->orderBy('title')->all();
        } elseif ($category = BusinessCategory::find()->where(['id' => $pid, 'sitemap_en' => 1])->one()) {
            /** @var BusinessCategory $category */
            $categoryList = $category->children(1)->andWhere($where)->orderBy('title')->all();
        } else {
            $categoryList = [];
        }
        echo Json::encode($categoryList);
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

    private function mb_ucfirst($str, $encoding='UTF-8')
    {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
               mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }

    public function actionProductCategoryList()
    {
        $pid = NULL;
        $where = [];
        if ($searchText = YII::$app->request->post("search")) {
            $where = $this->getWhereLike($searchText);
        }
        if (YII::$app->request->post("pid")) {
            $pid = YII::$app->request->post("pid");
            $root = ProductCategory::findOne(['id' => $pid]);
            $categorylist = $root->children(1)->select(['id', 'title'])->andWhere($where)->orderBy('title')->all();
        } else {
            $categorylist = ProductCategory::find()->select(['id', 'title'])->where($where)->roots()->orderBy('title')->all();
        }
        $parent = 0;
        if (($pid = YII::$app->request->post("pid")) and $root = ProductCategory::findOne(['id' => $pid])) {
            $parent = $root->parents(1)->one();
            $parent = isset($parent->id) ? $parent->id : $parent;
        }

        //убираем без категории со списка
        unset($categorylist[1444]);
        echo Json::encode(['items' => $categorylist, 'back_id' => (int)$parent]);
    }

    public function actionCityList()
    {
        $pid = null;
        $categorylist =[]; 
        $where = [];
        if($searchText = Yii::$app->request->post("search")){
            $where = $this->getWhereLike($searchText);
        }
        if (Yii::$app->request->post("pid")) {
            $pid = Yii::$app->request->post("pid");
            $old = Yii::$app->request->post("old");
            if(empty($old) || $searchText || ($searchText == '')) {
                $categorylist = City::find()->select(['id','title'])->where(['idRegion'=>$pid])->andWhere($where)->orderBy('title')->all();
            }   
        }
        else{
           $categorylist = Region::find()->select(['id','title'])->where($where)->orderBy('title')->all();
        }
        echo Json::encode($categorylist);
    }

    public function actionContact(){
        if(Yii::$app->request->isAjax){
            $data = Yii::$app->request->post();

            $idBusiness = $data['idBusiness'];
            $business = Business::findOne($idBusiness);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $result = '';

            if (isset($business->phone) && $business->phone != ''){
                $result = $business->phone;
            } else {
                $profile = \common\models\Profile::findOne(Yii::$app->user->id);

                if (isset($profile->phone)){
                    $result = $profile->phone;
                }
            }

            return ['contact' => $result];
        }
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

                if ($business_product_category){
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

    public function actionProductCategoryByBusiness()
    {
        $out = [];
        $selected = '';
        if (isset($_POST['depdrop_parents'])) {
            $parent = $_POST['depdrop_parents'][0];
            if (!empty($parent)) {
                #Предприятие по id
                $business = Business::find()->where(['id' => $parent])->one();

                $business_product_category = BusinessProductCategory::find()
                    ->select('*')
                    ->joinWith('product_category', 'product_category.id = product_category_id')
                    ->where(['business_category_id' => $business->idCategories])
                    ->all();

                if ($business_product_category){
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
                    $selected = '';
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

    public function actionPay($id = null)
    {
        $model = null;

        if ($id) {
            $userOwn = Business::find()->where(['idUser' => Yii::$app->user->id])->count();
            if ($userOwn > 0) {
                return $this->redirect(Yii::$app->urlManagerFrontend->createUrl('/site/support', true));
            }

            $model = Business::findOne($id);

            if ($model) {
                if ($model->idUser !== 1) {
                    throw new HttpException(403);
                }

                $model->idUser = Yii::$app->user->id;
                if ($model->save()) {
                    $model->refresh();
                }
            }
        }

        return $this->render('pay', ['model' => $model]);
    }

}
