<?php

namespace backend\controllers;

use common\models\Log;
use Yii;
use common\models\Product;
use common\models\File;
use common\models\CountViews;
use common\models\search\Product as ProductSearch;
use yii\web\NotFoundHttpException;
use backend\controllers\BaseAdminController;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends BaseAdminController
{
    public function actions()
    {
        return [
            'listcategories' => [
                'class' => 'common\extensions\NestedSelectCategory\Actions\GetCategory',
                'model' => 'common\models\ProductCategory',
            ],
        ];
    }
    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $_id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        $action = Yii::$app->request->post('action');
        if ($model->load(Yii::$app->request->post()) && !$action)
        {
            \Yii::$app->files->upload($model, 'image');
            \Yii::$app->files->upload($model, 'gallery');
            
            $model->setScenario('create');
            if ($model->save()) {
                Log::addAdminLog("product[create]  ID: {$model->_id}", $model->_id, Log::TYPE_PRODUCT);
                return $this->redirect(['view', 'id' => (string)$model->_id]);
            }
        }
        $model->setScenario('');
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
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

        $action = Yii::$app->request->post('action');
        if ($model->load(Yii::$app->request->post()) && !$action)
        {
            \Yii::$app->files->upload($model, 'image');
            \Yii::$app->files->upload($model, 'gallery');

            if (!$model->isNewRecord) {
                if ($model->image == '' && $actions != 'deleteImg')
                $model->image = $model->oldAttributes['image'];
            }

            if ($model->save()) {
                Log::addAdminLog("product[update]  ID: {$model->_id}", $model->_id, Log::TYPE_PRODUCT);
                return $this->redirect(['view', 'id' => (string)$model->_id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionDelete($id)
    {
        \Yii::$app->files->deleteFile($this->findModel($id), 'image');
        \Yii::$app->files->deleteFile($this->findModel($id), 'gallery');
        CountViews::deleteAll(['pidMongo'=>(string)$id, 'type'=>File::TYPE_PRODUCT]);
        $this->findModel($id)->delete();
        Log::addAdminLog("product[create]  ID: {$id}", $id, Log::TYPE_PRODUCT);
        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
