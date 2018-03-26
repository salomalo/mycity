<?php
/**
 * @var $this \yii\web\View
 * @var $transaction \common\models\Invoice
 * @var $business \common\models\Business
 */

use yii\helpers\Html;
use yii\helpers\Url;

$tariffs = $business->getTariffs(Yii::$app->user->identity);
$order = $tariffs[$business->price_type]['order'];

$callback_url = Url::to(['/business/callback', 'user_id' => Yii::$app->user->identity->id, 'id' => $business->id]);
?>

<tr>
    <td class="no"><?= $transaction->id ?></td>
    <td class="desc"><h3><?= Html::a($business->title, ['/business/view', 'id' => $business->id]) ?></h3>
        <?= $transaction->description?>
    </td>
    <td class="unit"><?= $transaction->amount?></td>
    <td class="qty"><?= date('Y-m-d') ?></td>
    <td class="total"><?= date('Y-m-d', time() + 3600 * 24 * 30) ?></td>
    <td class="qty">
        <?= Yii::$app->liqPay->checkoutPay($callback_url, $order, ['view' => 'office/business_pay']); ?>
    </td>
</tr>
