<?php

namespace backend\controllers;

use Yii;
use common\models\ParserDomain;
use common\models\search\ParserDomain as ParserDomainSearch;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use common\models\City;
use backend\controllers\BaseAdminController;

/**
 * ParserDomainController implements the CRUD actions for ParserDomain model.
 */
class ParserDomainController extends BaseAdminController
{
    /**
     * Lists all ParserDomain models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ParserDomainSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ParserDomain model.
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
     * Creates a new ParserDomain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ParserDomain();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ParserDomain model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ParserDomain model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ParserDomain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ParserDomain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ParserDomain::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionCity() {
        
        if (isset($_POST['depdrop_parents'])) {
            $id = $_POST['depdrop_parents'];
            
            $list = City::find()->where(['idRegion'=>$id])->orderBy('title')->asArray()->all();
            $selected  = '';
            if($list){
                foreach ($list as $i => $item){
                    $out[] = ['id' => $item['id'], 'name' => $item['title']];
                    if ($i == 0) {
                        $selected = $item['id'];
                    }
                }
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }    
        echo Json::encode(['output' => '', 'selected'=>'']);
    }
}
