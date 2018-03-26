<?php

namespace office\controllers;

use common\models\search\Ticket as TicketSearch;
use common\models\Ticket;
use common\models\TicketHistory;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * TicketController implements the CRUD actions for Ticket model.
 */
class TicketController extends DefaultController
{
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
//                    'close' => ['post'],
//                ],
//            ],
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        //'actions' => ['index'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//        ];
    }

    /**
     * Lists all Ticket models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->andWhere(['idUser' => Yii::$app->user->id]);

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
        if (\Yii::$app->user->id != $model->idUser){
           throw new NotFoundHttpException('Not Found','404');
        }         
        if ($model->status==Ticket::STATUS_ANSWER){
            $model->status = Ticket::STATUS_USERREVIEW;
            $model->save(false, ['status']);
        }
        
        $model_history = new TicketHistory();
        if($model_history->load(Yii::$app->request->post())) {
           $model_history->idTicket   = $id; 
           $model_history->idUser     = Yii::$app->user->id;
           $model_history->dateCreate = date('Y-m-d H:i:s');
           if($model_history->save()){
            $model->status = Ticket::STATUS_QUESTION;
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
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ticket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ticket::find()->where(['id' => $id, 'idUser' => Yii::$app->user->identity->id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ticket();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->pid == ''){
                $model->pid = 0;
            }

            $model->idCity = User::findOne($model->idUser)->city_id;
            $model->status = Ticket::STATUS_QUESTION;

            if ($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
            return $this->render('create', [
                'model' => $model,
                'history' => [],
            ]);
    }

    /**
     * Deletes an existing Ticket model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
//    public function actionDelete($id)
//    {
//        TicketHistory::deleteAll(['idTicket'=>$id]);
//        
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }

    public function actionClose($id)
    {
        $model = $this->findModel($id);
        if (\Yii::$app->user->id != $model->idUser){
           throw new NotFoundHttpException('Not Found','404');
        }
        $model->status = Ticket::STATUS_CLOSED;
        $model->save(false, ['status']);

        return $this->redirect(['index']);
    }
}
