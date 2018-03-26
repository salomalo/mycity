<?php
namespace m\controllers;

use common\models\BusinessAddress;
use common\models\BusinessTime;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use frontend\models\PasswordResetRequestForm;
use frontend\models\SignupForm;
use backend\extensions\S3UploadedFile;
use yii\filters\VerbFilter;
use yii\bootstrap\BootstrapPluginAsset;
use yii\widgets\Breadcrumbs;
use common\models\Business;
use common\models\Log;
use dektrium\user\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $layout = 'site';
    public $breadcrumbs = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
       $model = new Business;

        if ($model->load(Yii::$app->request->post())) {
            
            \Yii::$app->files->upload($model, 'image');
            
            if($model->save()){
                $this->addTime($model->id);
                $this->addAddress($model->id);
                Log::addUserLog("business[create]  ID: {$model->id}", $model->id, Log::TYPE_BUSINESS);
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
                'address' => [],
            ]);
        }
//        return $this->render('index');
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
}
