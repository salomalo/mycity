<?php
/**
 * @var $this \yii\web\View
 * @var $invoice \common\models\Invoice
 * @var $business Business
 * @var $user \common\models\User
 * @var $order \common\components\LiqPay\models\Order
 * @var $sum double
 */

use common\models\Business;
use common\models\Invoice;
use yii\helpers\Url;

$this->title = Yii::t('business', 'Invoices');
$this->params['breadcrumbs'][] = ['label' => Yii::t('transactions', 'Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$redirect_url = Yii::$app->urlManager->createAbsoluteUrl(['/business/view', 'id' => $invoice->object_id, 'message' => 'Оплата прошла успешно']);
?>

<div class="invoice">
    <header class="clearfix">
        <div id="company">
            <div class="brand-logo">
                <a href="" class="logo"></a>
            </div>
            <div class="company-info">
                <h2 class="name">CityLife</h2>
                <div class="company-mail"><a href="mailto:support@citylife.info">support@citylife.info</a></div>
            </div>
        </div>
    </header>
    <main>
        <div id="details" class="clearfix">
            <div id="client">
                <div class="to">Инвойис для:</div>
                <h2 class="name"><?= $user->username?></h2>
                <div class="email"><a href="mailto:john@example.com"><?= $user->email ?></a></div>
            </div>
            <div class="status-invoice">
                <?php
                if ($invoice->paid_status == Invoice::PAID_NO) {
                    echo 'Статус: <span class="label label-danger">Не оплачено</span>';
                } elseif ($invoice->paid_status == Invoice::PAID_YES) {
                    echo 'Статус: <span class="label label-success">Оплачено</span>';
                }
                ?>
            </div>
            <div id="invoice">
                <h1>Счет № <?= $invoice->id ?></h1>
                <div class="date"><?= Yii::t('transactions', 'Date_create') ?>: <?= date('Y-m-d') ?></div>
            </div>
        </div>
        <table border="0" cellspacing="0" cellpadding="0" class="invoice">
            <thead>
            <tr>
                <th class="no">#</th>
                <th class="desc">Описание</th>
                <th class="unit">Цена</th>
                <th class="qty">С</th>
                <th class="total">По</th>
            </tr>
            </thead>
            <?php if ($invoice) : ?>
                <tbody>
                    <?= $this->render('_view_short', [
                        'transaction' => $invoice,
                        'business' => Business::findOne($invoice->object_id),
                    ]) ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2">Промежуточная сумма</td>
                    <td><?= $sum ?></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2">Сумма</td>
                    <td><?= $order->amount ?></td>
                </tr>
                </tfoot>
            <?php endif; ?>
        </table>

        <?php if ($invoice->paid_status === Invoice::PAID_NO) : ?>
            <div class="liq-btn-green">
                <?= Yii::$app->liqPay->checkoutPay($redirect_url, $order, ['view' => 'office/business_pay']); ?>
            </div>
        <?php endif; ?>
    </main>
</div>