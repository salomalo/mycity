<?php
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Orders */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model Orders */

use common\models\Orders;
use common\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use common\models\PaymentType;

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-index">

    <p>
        <?= Html::a('Create Orders', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'options' => ['width'=>'70px'],
            ],
            [
                'attribute' => 'idUser',
                'options' => ['width'=>'250px'],
                'value' => function ($model) {
                    return isset($model->user->username) ? $model->user->username : '';
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => User::getAll(),
                    'attribute' => 'idUser',
                    'options' => [
                        'placeholder' => 'Select a user ...',
                        'id' => 'idUser',
                    ],
                    'pluginOptions' => ['allowClear' => true]
                ]),
            ],
            [
                'attribute' => 'idCity',
                'options' => ['width'=>'200px'],
                'value' => function ($model) {
                    return isset($model->city->title) ? $model->city->title : '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => Yii::$app->params['adminCities']['select'],
                    'attribute' => 'idCity',
                    'options' => [
                        'placeholder' => 'Select a city ...',
                        'id' => 'idCity',
                    ],
                    'pluginOptions' => ['allowClear' => true]
                ]),
            ],
            [
                'attribute' => 'address',
                'value' => function (Orders $model) {
                    return $model->address;
                }
            ],
            [
                'attribute' => 'paymentType',
                'options' => ['width'=>'250px'],
                'value' => function (Orders $model) {
                    return $model->payment ? $model->payment->paymentType->title : '';
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => PaymentType::getAll(),
                    'attribute' => 'paymentType',
                    'options' => [
                        'placeholder' => 'Select a type ...',
                        'id' => 'paymentType',
                    ],
                    'pluginOptions' => ['allowClear' => true]
                ]),
            ],
            [
                'attribute' => 'dateCreate',
                'options' => ['width'=>'250px'],
                'value' => function ($model) {
                    return ($model->dateCreate)? date('d-m-Y', $model->dateCreate) : '';
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
