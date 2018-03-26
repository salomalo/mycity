<?php

namespace backend\controllers;

use yii;
use common\models\Ticket;
use backend\models\search\Ticket as TicketSearch;
use yii\web\NotFoundHttpException;
use common\models\TicketHistory;
use common\models\Log;

/**
 * TicketController implements the CRUD actions for Ticket model.
 */
class TicketController extends BaseAdminController
{
    /**
     * Lists all Ticket models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ticket model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if ($model->status==Ticket::STATUS_QUESTION){
            $model->status = Ticket::STATUS_ADMINREVIEW;
            $model->save(false, ['status']);
        }
        
        $model_history = new TicketHistory();
        if($model_history->load(Yii::$app->request->post())) {
           $model_history->idTicket   = $id; 
           $model_history->idUser     = 1;//Yii::$app->user->id;
           $model_history->dateCreate = date('Y-m-d H:i:s');
           if($model_history->save()){
            $model->status = Ticket::STATUS_ANSWER;
            $model->save(false, ['status']);               
           }
        }
        
        $history = TicketHistory::find()->where(['idTicket'=>$id])->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'history' => $history,
            'model_history' => new TicketHistory(),
        ]);
    }

    /**
     * Creates a new Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ticket();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::addAdminLog("ticket[create]  ID: {$model->id}", $model->id, Log::TYPE_TICKET);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Ticket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())){ 
            if ($model->type!=Ticket::TYPE_COMPANY){
                $model->pid =0;
            }
            if($model->save()){
                Log::addAdminLog("ticket[update]  ID: {$model->id}", $model->id, Log::TYPE_TICKET);
            }
           // return $this->redirect(['view', 'id' => $model->id]);
        }  
            $history = TicketHistory::find()->where(['idTicket'=>$model->id])->all();
            return $this->render('update', [
                'model' => $model,
                'history' => $history,
            ]);
    }

    /**
     * Deletes an existing Ticket model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        TicketHistory::deleteAll(['idTicket'=>$id]);
        
        $this->findModel($id)->delete();
        Log::addAdminLog("ticket[delete]  ID: {$id}", $id, Log::TYPE_TICKET);

        return $this->redirect(['index']);
    }
    
    public function actionClose($id)
    {
        $model = $this->findModel($id);
        $model->status = Ticket::STATUS_CLOSED;
        $model->save(false, ['status']);
       
        return $this->redirect(['index']);
    }
    /**
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ticket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ticket::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
