<?php
namespace office\controllers;

use common\components\LiqPay\LiqPayCurrency;
use common\components\LiqPay\models\Order;
use common\models\Business;
use common\models\Product;
use common\models\User;
use yii;
use common\models\File;
use common\models\search\Invoice;
use \office\models\search\Invoice as InvoiceSearch;
use yii\web\HttpException;

class TransactionsController extends DefaultController
{
    public function actions()
    {
        return ['error' => ['class' => 'yii\web\ErrorAction']];
    }

    public function actionIndex()
    {
//        $invoices = Invoice::find()->where([
//            'object_type' => File::TYPE_BUSINESS,
//            'user_id' => Yii::$app->user->id,
//        ])->orderBy(['created_at' => SORT_DESC])->all();
//
//        return $this->render('index', ['invoices' => $invoices]);

        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->query->addOrderBy('id DESC');
        $dataProvider->pagination->pageSize= 10;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionView($id)
    {
        $invoice = Invoice::findOne($id);

        $business = Business::findOne($invoice->object_id);

        $user = User::findOne(Yii::$app->user->id);

        if (!$invoice or ($invoice->user_id !== Yii::$app->user->id)) {
            throw new HttpException(404);
        }

        $sum = $invoice->amount;

        $order_id = $invoice->order_id == '' ? $invoice->id . '_old_invoice' : $invoice->order_id;
        $order = new Order($sum , LiqPayCurrency::UAH, "Управление предприятиями", $order_id);

        if (!$business) {
            return $this->render('invoice_error', [
                'message' => 'Предприятие было удалено',
                'invoice' => $invoice,
                'user' => $user,
                'sum' => $sum,
                'order' => $order,
            ]);
        }

        return $this->render('view', [
            'invoice' => $invoice,
            'user' => $user,
            'sum' => $sum,
            'order' => $order,
        ]);
    }
}
