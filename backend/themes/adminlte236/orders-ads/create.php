<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OrdersAds */

$this->title = 'Create Orders Ads';
$this->params['breadcrumbs'][] = ['label' => 'Orders Ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-ads-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
