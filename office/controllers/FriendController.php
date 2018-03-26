<?php

namespace office\controllers;

use common\models\User;
use common\models\Friend;
use common\models\search\Friend as FriendSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * FriendController implements the CRUD actions for Friend model.
 */
class FriendController extends DefaultController
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
//                    'deleteFriend' => ['post'],
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
     * Lists all Friend models.
     * @param null $status
     * @return mixed
     */
    public function actionIndex($status = null)
    {
        $searchModel = new FriendSearch();
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true, $status);
        
        $model = Friend::find()->select(['idFriend'])->where(['idUser'=>Yii::$app->user->id])->asArray()->all();
        $arrFriend = [];
        foreach ($model as $item) {
            $arrFriend[] = $item['idFriend'];
        }
        
        $render = 'index';
        
        if ($status == User::TYPE_FRIEND_REMOVED) {
            $render = 'removed';
        }

        return $this->render($render, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'arrFriend' => $arrFriend,
        ]);
    }
    
    public function actionOffer($id=null, $do=null)
    {
        $searchModel = new FriendSearch();
        
        if($id && $do){
            if($do == 'take'){
                $friend = Friend::find()->where(['id'=>$id])->one();
                $friend->status = User::TYPE_FRIEND_CONFIRMED;
                $friend->save();
                
                $model = new Friend();
                $model->idUser = Yii::$app->user->id;
                $model->idFriend = $friend->idUser;
                $model->status = User::TYPE_FRIEND_CONFIRMED;
                $model->save();
            }
            
            if($do == 'refuse'){
                $friend = Friend::findOne($id);
                $friend->delete();
            }
        }
        
        $dataProvider = $searchModel->searchOffer(Yii::$app->request->queryParams);
        
        return $this->render('offer', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Friend model.
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
     * Finds the Friend model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Friend the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Friend::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new Friend model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param null $id
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        if($id){
            $friend = new Friend();
            $friend->idUser = Yii::$app->user->id;
            $friend->idFriend = $id;
            $friend->status = User::TYPE_FRIEND_INVITED;
            $friend->save();
        }
        $searchModel = new FriendSearch();

        $dataProvider = $searchModel->searchAccount(Yii::$app->request->queryParams);

        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing Friend model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param bool $full
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id, $full = false)
    {
        $model = $this->findModel($id);

        if(!$full){
            $friend = Friend::find()->where(['idUser'=>$model->idFriend, 'idFriend'=>$model->idUser])->one();
            if($friend){
                $friend->delete();
            }
            $model->status = User::TYPE_FRIEND_REMOVED;
            $model->save();
        }
        else{
            $model->delete();
        }

        return $this->redirect(['index']);
    }

    public function actionRestoreFriend($id)
    {
        if ($id) {
            $model = $this->findModel($id);

            $friend = new Friend();
            $friend->idUser = $model->idFriend;
            $friend->idFriend = $model->idUser;
            $friend->status = User::TYPE_FRIEND_CONFIRMED;
            $friend->save();

            $model->status = User::TYPE_FRIEND_CONFIRMED;
            $model->save('status');
        }
        return $this->redirect(['index']);
    }
}
