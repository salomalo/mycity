<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\OrdersAds;

/* @var $this yii\web\View */
/* @var $model common\models\OrdersAds */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders Ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-ads-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Список', ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'pid',
            'idAds',
            'countAds',
            
            [
                'attribute' => 'idBusiness',
                'value' => ($model->business)? $model->business->title : '',
            ],
            [
                'attribute' => 'status',
                'value' => OrdersAds::$statusList[$model->status],
            ],
        ],
    ]) ?>

</div>
