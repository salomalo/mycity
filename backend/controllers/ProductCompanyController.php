<?php

namespace backend\controllers;

use common\models\Log;
use common\models\ProductCategoryCategory;
use Yii;
use common\models\ProductCompany;
use common\models\search\ProductCompany as ProductCompanySearch;
use yii\web\NotFoundHttpException;
use backend\controllers\BaseAdminController;

/**
 * ProductCompanyController implements the CRUD actions for ProductCompany model.
 */
class ProductCompanyController extends BaseAdminController
{
    /**
     * Lists all ProductCompany models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductCompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductCompany model.
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
     * Creates a new ProductCompany model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductCompany();

        if ($model->load(Yii::$app->request->post())) {
            
            \Yii::$app->files->upload($model, 'image');
            
            if($model->save()){
                $this->saveCompaniesCats($model);
                Log::addAdminLog("product company[create]  ID: {$model->id}", $model->id, Log::TYPE_PRODUCT_COMPANY);
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProductCompany model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $actions = '')
    {
        $model = $this->findModel($id);
        
        if($actions == 'deleteImg'){
                \Yii::$app->files->deleteFile($model, 'image');
                $model->image = '';
                $model->save();
        }

        if ($model->load(Yii::$app->request->post())) {
            
            \Yii::$app->files->upload($model, 'image');
            
            if($model->save())
            {
                $this->saveCompaniesCats($model);
                Log::addAdminLog("product company[update]  ID: {$model->id}", $model->id, Log::TYPE_PRODUCT_COMPANY);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param ProductCompany $model
     */
    protected function saveCompaniesCats($model)
    {
        if ($categories = Yii::$app->request->post('categories'))
        {
            ProductCategoryCategory::deleteAll(['ProductCompany' => $model->id]);
            foreach($categories as $category)
            {
                $cat = new ProductCategoryCategory();
                $cat->ProductCompany = $model->id;
                $cat->ProductCategory = $category;
                $cat->save();
            }
        }
    }

    /**
     * Deletes an existing ProductCompany model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        \Yii::$app->files->deleteFile($this->findModel($id), 'image');
        $this->findModel($id)->delete();
        Log::addAdminLog("product company[delete]  ID: {$id}", $id, Log::TYPE_PRODUCT_COMPANY);
        return $this->redirect(['index']);
    }

    /**
     * Finds the ProductCompany model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductCompany the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductCompany::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
