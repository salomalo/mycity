<?php

namespace backend\controllers;

use backend\models\Admin;
use common\models\LiqpayPayment;
use yii;
use common\models\Invoice;
use common\models\search\Invoice as InvoiceSearch;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TransactionsController implements the CRUD actions for Transactions model.
 */
class InvoiceController extends Controller
{
    /**
     * @inheritdoc
     */
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
                'actions' => ['delete' => ['POST']],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (Yii::$app->user->identity && (Yii::$app->user->identity->level !== Admin::LEVEL_SUPER_ADMIN)) {
            return $this->redirect(['/']);
        }
        return parent::beforeAction($action);
    }

    /**
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Invoice model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    /**
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

//    public function actionCheckStatus()
//    {
//        /** @var Invoice[] $invoices */
//        $invoices = Invoice::find()->all();
//
//        foreach ($invoices as $invoice) {
//            $status = Yii::$app->liqPay->getStatus($invoice->order_id);
//            if (!is_object($status)) {
//                continue;
//            }
//            $payment = LiqpayPayment::find()->where(['order_id' => $invoice->order_id])->one();
//            if (!$payment) {
//                $payment = new LiqpayPayment();
//                $payment->status = $status->status;
//                $payment->order_id = $invoice->order_id;
//            }
//            $payment->data = Json::encode($status);
//            if (!$payment->save()) {
//                throw new HttpException(500, implode(',', $payment->firstErrors));
//            }
//        }
//    }
}
