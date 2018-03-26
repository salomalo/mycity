<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentType */

$this->title = 'Update Payment Type: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Payment Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payment-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
