<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\LiqpayPayment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('business_custom_field', 'Liqpay Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="liqpay-payment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('business_custom_field', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Список', ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'status',
            'order_id',
            'action',
            'amount',
            'currency',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <?php $model->data ? VarDumper::dump(Json::decode(base64_decode($model->data)), 10, true) : false ?>

</div>
