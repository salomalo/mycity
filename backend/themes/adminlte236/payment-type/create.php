<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PaymentType */

$this->title = 'Create Payment Type';
$this->params['breadcrumbs'][] = ['label' => 'Payment Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
