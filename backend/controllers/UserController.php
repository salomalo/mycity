<?php

namespace backend\controllers;

use common\models\SocialAccount;
use yii;
use common\models\User;
use common\models\search\User as UserSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Log;
use yii\web\HttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model->bindProfile(),
        ]);
    }

    public function actionCreate()
    {
        $model = new User();
        $model->setScenario('create_backend');

        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword($model['password_hash']);
            $model->generateAuthKey();

            if ($model->save()) {
                Log::addAdminLog("account[create]  ID: {$model->id}", $model->id, Log::TYPE_ACCOUNT);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('update_backend');

        /* @var $model User*/
        $old_password_hash =$model->password_hash;

        if ($model->load(Yii::$app->request->post())) {
            if($new_pass = Yii::$app->request->post('User')['password_hash']) $model->setPassword($new_pass);
            else $model->password_hash = $old_password_hash;
            if($model->save()) {
                Log::addAdminLog("account[update]  ID: {$model->id}", $model->id, Log::TYPE_ACCOUNT);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model->bindProfile(),
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Log::addAdminLog("account[delete]  ID: {$id}", $id, Log::TYPE_ACCOUNT);
        return $this->redirect(['index']);
    }

    public function actionSocial($id)
    {
        if (!(int)$id) throw new HttpException(404);
        $model = SocialAccount::findModel($id);
        if (!$model) throw new HttpException(404);

        $data = json_decode($model->data);
        $dataAttr = array();

        foreach($data as $key => $value){
            if (is_string($value)) {
                $dataAttr[] = $key;
            } elseif(is_array($value) or is_object($value)) {
                $dataAttr[] = [
                    'attribute' => $key,
                    'value' => json_encode($value),
                ];
            }
        }

        return $this->render('social', [
            'model' => $model,
            'data' => $data,
            'dataAttr' => $dataAttr,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
