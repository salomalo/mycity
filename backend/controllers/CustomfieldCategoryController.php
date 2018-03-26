<?php

namespace backend\controllers;

use Yii;
use common\models\CustomfieldCategory;
use common\models\search\CustomfieldCategory as CustomfieldCategorySearch;
use yii\web\NotFoundHttpException;
use common\models\ProductCustomfield;
use backend\controllers\BaseAdminController;
use common\models\Log;

/**
 * CustomfieldCategoryController implements the CRUD actions for CustomfieldCategory model.
 */
class CustomfieldCategoryController extends BaseAdminController
{
    /**
     * Lists all CustomfieldCategory models.
     * @param null $list
     * @return mixed
     */
    public function actionIndex($list = null)
    {
        $searchModel = new CustomfieldCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if($list){
            return $this->render('index_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        }
        
        $id = 0;
        if ($post = Yii::$app->request->post('ProductCustomfield')) {
            $id = (int)$post['idCategoryCustomfield'];
        }
        
        $customfields = [];
       
        $customfield = ProductCustomfield::find()->where(['idCategoryCustomfield' => $id])->orderBy('order ASC')->all();
        
        $i=0;
        foreach ($customfield as $item){
            $customfields[$i]['customfieldCategory'] = ($item->categoryCustomfield)? $item->categoryCustomfield->title : '';
            $customfields[$i]['title'] = $item->title;
            $customfields[$i]['id'] = $item->id;
            
            $valuesList = $item->customfieldValue;
            
            if(is_array($valuesList) && !empty($valuesList)){
                foreach ($valuesList as $item){
                    $customfields[$i]['value'][] = $item->value.'<br>';
                }
            }else{
                $customfields[$i]['value'][]  = '';
            }
            $i++;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'idCat' => $id,
            'customfields' => $customfields,
        ]);
    }

    /**
     * Displays a single CustomfieldCategory model.
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
     * Creates a new CustomfieldCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CustomfieldCategory();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addAdminLog("customfield category[create]  ID: {$model->id}", $model->id, Log::TYPE_CUSTOM_FIELD_CATEGORY);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CustomfieldCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addAdminLog("customfield category[update]  ID: {$model->id}", $model->id, Log::TYPE_CUSTOM_FIELD_CATEGORY);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CustomfieldCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Log::addAdminLog("customfield category[delete]  ID: {$id}", $id, Log::TYPE_CUSTOM_FIELD_CATEGORY);

        return $this->redirect(['index']);
    }

    /**
     * Finds the CustomfieldCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CustomfieldCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CustomfieldCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
