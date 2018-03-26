<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\LiqpayPayment */

$this->title = Yii::t('business_custom_field', 'Create Liqpay Payment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('business_custom_field', 'Liqpay Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="liqpay-payment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
