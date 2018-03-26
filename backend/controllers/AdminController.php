<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\search\AdminSearch;
use yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use backend\controllers\BaseAdminController;
use common\models\Log;

/**
 * AccountController implements the CRUD actions for Account model.
 */
class AdminController extends BaseAdminController
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->identity && (Yii::$app->user->identity->level !== Admin::LEVEL_SUPER_ADMIN)) {
            throw new HttpException(403);
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all Account models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Creates a new Account model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Admin();
        if ($model->load(Yii::$app->request->post())){      
            $model->setPassword($model['password_hash']);

            if ($model->save()){
                Log::addAdminLog("admin[create] ID: {$model->id}", $model->id, Log::TYPE_ADMIN_LIST);
                return $this->redirect(['index']);
            }

        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Account model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $hash = $model->password_hash;
        if ($model->load(Yii::$app->request->post())){
            $post = Yii::$app->request->post('Admin');

            if (!empty($post['password_hash'])) {
                $model->setPassword($post['password_hash']);
            } else {
                $model->password_hash = $hash;
            }
            if ($model->save()){
                Log::addAdminLog("admin[update] ID: {$model->id}", $model->id, Log::TYPE_ADMIN_LIST);
                return $this->redirect(['index']);
            }

        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
    /**
     * Deletes an existing Account model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Log::addAdminLog("admin[delete] ID: {$id}", $id, Log::TYPE_ADMIN_LIST);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Account model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
