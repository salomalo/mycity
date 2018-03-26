<?php

namespace backend\controllers;

use Yii;
use common\models\AfishaCategory;
use common\models\search\AfishaCategory as AfishaCategorySearch;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\models\Log;

/**
 * AfishaCategoryController implements the CRUD actions for AfishaCategory model.
 */
class AfishaCategoryController extends BaseAdminController
{
    /**
     * Lists all AfishaCategory models.
     * @param bool $isFilm
     * @return mixed
     */
    public function actionIndex($isFilm = false)
    {
        $searchModel = new AfishaCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if($isFilm){
            $dataProvider->query->andWhere(['isFilm' => 1]);
        }
        else{
            $dataProvider->query->andWhere(['isFilm' => 0]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'isFilm' => $isFilm,
        ]);
    }

    /**
     * Displays a single AfishaCategory model.
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
     * Creates a new AfishaCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param bool $isFilm
     * @return mixed
     */
    public function actionCreate($isFilm = false)
    {
        $model = new AfishaCategory();

        if ($model->load(Yii::$app->request->post())) {
            
            \Yii::$app->files->upload($model, 'image');
            
            if($model->save()){
                Log::addAdminLog("afisha category[create] ID: {$model->id}", $model->id, Log::TYPE_AFISHA_CATEGORY);
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
                'isFilm' => $isFilm,
            ]);
        }
    }

    /**
     * Updates an existing AfishaCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $actions
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $actions = '')
    {
        $model = $this->findModel($id);
        /* @var $model AfishaCategory */
        if ($actions === 'deleteImg') {
            \Yii::$app->files->deleteFile($model, 'image');
            $model->image = '';
            $model->save();
            $model = $this->findModel($id);
        } elseif ($model->load(Yii::$app->request->post())) {
            \Yii::$app->files->upload($model, 'image');
            if ($model->save()) {
                Log::addAdminLog("afisha category[update] ID: {$model->id}", $model->id, Log::TYPE_AFISHA_CATEGORY);
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        }
        return $this->render('update', [
            'model' => $model,
            'isFilm' => ($model->isFilm == 1) ? 1 : null,
        ]);
    }

    /**
     * Deletes an existing AfishaCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        \Yii::$app->files->deleteFile($this->findModel($id), 'image');
        $this->findModel($id)->delete();
        Log::addAdminLog("afisha category[delete] ID: {$id}", $id, Log::TYPE_AFISHA_CATEGORY);

        return $this->redirect(['index', 'isFilm' => ($model->isFilm == 1)? 1 : NULL]);
    }

    /**
     * Finds the AfishaCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AfishaCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AfishaCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
