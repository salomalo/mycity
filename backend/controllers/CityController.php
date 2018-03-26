<?php

namespace backend\controllers;

use common\models\Business;
use Yii;
use common\models\City;
use common\models\search\City as CitySearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use backend\controllers\BaseAdminController;
use common\models\Log;
use common\models\CityDetail;

/**
 * CityController implements the CRUD actions for City model.
 */
class CityController extends BaseAdminController
{
    /**
     * Lists all City models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single City model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model->bindCityInfo(),
        ]);
    }

    /**
     * Creates a new City model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new City();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addAdminLog("city[create]  ID: {$model->id}", $model->id, Log::TYPE_CITY);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing City model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $token = $model->vk_public_admin_token;
        
        if ($model->load(Yii::$app->request->post())) {
            !empty($model->vk_public_admin_token) ?: $model->vk_public_admin_token = $token;
            
            if ($model->save()) {
                Log::addAdminLog("city[update]  ID: {$model->id}", $model->id, Log::TYPE_CITY);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        $model->vk_public_admin_token = null;

        return $this->render('update', ['model' => $model->bindCityInfo()]);
    }

    /**
     * Deletes an existing City model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Log::addAdminLog("city[delete]  ID: {$id}", $id, Log::TYPE_CITY);

        return $this->redirect(['index']);
    }

    /**
     * Finds the City model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return City the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = City::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
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
            }
        } else {
            $cities = City::find()->where(['id' => Yii::$app->params['activeCitys']])->select(['id', 'title'])->all();
            $cities = ArrayHelper::map($cities, 'id', 'title');
            foreach ($cities as $id => $name) $out[] = ['id' => $id, 'name' => $name];
        }
        echo Json::encode(['output' => $out, 'selected' => (string)$selected]);
    }
}
