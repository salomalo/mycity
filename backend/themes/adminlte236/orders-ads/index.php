<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\Orders;
use common\models\OrdersAds;
use common\models\Business;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OrdersAds */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders Ads';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-ads-index">

    <p>
        <?= Html::a('Create Orders Ads', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'pid',
                'options' => ['width'=>'150px'],
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(Orders::find()->all(),'id','id'),
                    'attribute' => 'pid',
                    'options' => [
                            'placeholder' => 'Select a pid ...',
                            'id' => 'pid',
                            //'multiple' => true,
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],
            'idAds',
            'countAds',
            
            [
                'attribute' => 'idBusiness',
                'options' => ['width'=>'250px'],
                'value' => function ($model) {
                    return ($model->business)? $model->business->title : '';
                },
//                'filter'    => Select2::widget([
//                    'model' => $searchModel,
//                    'data' => ArrayHelper::map(Business::find()->select(['id', 'title'])->all(),'id','title'),
//                    'attribute' => 'idBusiness',
//                    'options' => [
//                            'placeholder' => 'Select a business ...',
//                            'id' => 'idBusiness',
//                            //'multiple' => true,
//                            ],
//                    'pluginOptions' => [
//                            'allowClear' => true,
//                    ]
//                ]),
            ],
            
            [
                'attribute' => 'status',
                'options' => ['width'=>'200px'],
                'value' => function ($model) {
                    return OrdersAds::$statusList[$model->status];
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => OrdersAds::$statusList,
                    'attribute' => 'status',
                    'options' => [
                            'placeholder' => 'Select a status ...',
                            'id' => 'status',
                            //'multiple' => true,
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
