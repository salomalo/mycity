<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\LiqpayPayment */

$this->title = Yii::t('business_custom_field', 'Update {modelClass}: ', [
    'modelClass' => 'Liqpay Payment',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('business_custom_field', 'Liqpay Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('business_custom_field', 'Update');
?>
<div class="liqpay-payment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
