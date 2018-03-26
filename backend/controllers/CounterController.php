<?php

namespace backend\controllers;

use yii;
use common\models\Counter;
use common\models\search\Counter as CounterSearch;
use yii\db\Exception as DbException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CounterController implements the CRUD actions for Counter model.
 */
class CounterController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => false, 'roles' => ['?']],
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
        ];
    }

    /**
     * Lists all Counter models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CounterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Counter model.
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
     * Creates a new Counter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws DbException
     */
    public function actionCreate()
    {
        $model = new Counter();

        if ($model->load(Yii::$app->request->post())) {
            $status = true;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$model->save()) {
                    throw new DbException(implode(', ', $model->firstErrors));
                }
                $model->createCityLinks();
                $transaction->commit();
            } catch (DbException $e) {
                $transaction->rollBack();
                $status = false;
                $model->addError('cities_input', $e->getMessage());
            }

            if ($status) {
                Counter::clearCache();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Counter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $status = true;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->updateCityLinks();
                if (!$model->save()) {
                    throw new DbException('Error while save counter', $model->firstErrors);
                }
                $transaction->commit();
            } catch (DbException $e) {
                $transaction->rollBack();
                $status = false;
                $model->addError('cities_input', $e->getMessage());
            }

            if ($status) {
                Counter::clearCache();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        $model->cities_input = $model->citiesId;

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing Counter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Counter::clearCache();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Counter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Counter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Counter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
