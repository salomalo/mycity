<?php
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\Business $searchModel
 * @var common\models\Orders $order
 */

use common\models\Orders;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Заказ номер ' . $order->id;
$this->params['breadcrumbs'][] = $this->title;
?>

<table style="margin-bottom: 50px">
    <tr>
        <td><strong>Статус:</strong></td>
        <td><strong><?= isset($order->status) ? Orders::$statusList[$order->status] : '' ?></strong></td>
    </tr>
    <tr>
        <td>Способ оплаты:</td>
        <td><?= isset($order->payment->paymentType->title) ?  $order->payment->paymentType->title : ''?></td>
    </tr>
    <tr>
        <td style="padding-right: 20px">Cпособ доставки/Cлужба доставки:</td>
        <td><?= $order->delivery ?></td>
    </tr>
    <tr>
        <td>Отделение:</td>
        <td><?= $order->office ?></td>
    </tr>
    <tr>
        <td>Адресс:</td>
        <td><?= $order->address ?></td>
    </tr>
    <tr>
        <td>Телефон:</td>
        <td><?= $order->phone ?></td>
    </tr>
    <tr>
        <td>Ф.И.О.:</td>
        <td><?= $order->fio ?></td>
    </tr>
</table>

<?= GridView::widget([
    'layout' => "{items}\n{pager}",
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'idAds',
            'value' => function ($model) {
                return ($model->ads)? $model->ads->title : '';
            },
            'filter' => false,
        ],
        [
            'attribute' => 'countAds',
            'filter' => false,
        ],
        [
            'attribute' => 'idBusiness',
            'value' => function ($model) {
                return ($model->business)? $model->business->title : '';
            },
            'filter' => false,
        ],
        [
            'label' => Yii::t('app', 'Providers'),
            'value' => function ($model) {
                if (isset($model->ads->provider->title)){
                    $url = Url::to(['/provider/view', 'id' => $model->ads->provider->id]);
                    $link = Html::a($model->ads->provider->title, $url, ['target' => '_blank']);
                    return $link;
                } else {
                    return '';
                }
            },
            'format' => 'raw',
            'filter' => false,
        ],
    ],
]); ?>

<div class="confirm-order">
    <?php if ($order->status != Orders::STATUS_CANCEL) :?>
        <?= Html::a('Подтвердить', ['/order/confirm-order', 'id' => $order->id]) ?>
        <?= Html::a('Отменить', ['/order/cancel-order', 'id' => $order->id]) ?>
    <?php endif; ?>
</div>