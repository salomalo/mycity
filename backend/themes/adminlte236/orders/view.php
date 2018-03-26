<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-view">

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
            [
                'attribute' => 'idUser',
                'value' => $model->user ? $model->user->username : '',
            ],
            [
                'attribute' => 'idCity',
                'value' => $model->city ? $model->city->title : '',
            ],
            [
                'attribute' => 'address',
                'value' => $model->address,
            ],
            [
                'attribute' => 'phone',
                'value' => $model->phone,
            ],
            [
                'attribute' => 'fio',
                'value' => $model->fio,
            ],
            'description:html',
            [
                'attribute' => 'paymentType',
                'value' => $model->payment ? $model->payment->paymentType->title : '',
            ],
            [
                'attribute' => 'dateCreate',
                'value' => $model->dateCreate ? date('d-m-Y', $model->dateCreate) : '',
            ],
        ],
    ]) ?>

</div>
