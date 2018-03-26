<?php
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\WorkResume */
/* @var $dataProvider yii\data\ActiveDataProvider */

use common\models\Orders;
use common\models\UserPaymentType;
use yii\grid\GridView;
use kartik\widgets\Select2;
use common\models\City;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('order', 'Orders_to_factories');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout'=>"<div class=\"box-body\">{items}</div>\n<div class=\"box-footer clearfix\"><div class='pull-right'>{pager}</div></div>\n{summary}",
    'columns' => [
        [
            'attribute' => 'id',
            'label' => '№ заказа',
            'options' => ['width'=>'100px'],
        ],
        [
            'attribute' => 'idCity',
            'options' => ['width'=>'200px'],
            'value' => function ($model) {
                return $model->city->title;
            },
            'filter' => Select2::widget([
                'model' => $searchModel,
                'data' => ArrayHelper::map(City::find()->orderBy('title ASC')->all(),'id','title'),
                'attribute' => 'idCity',
                'options' => [
                        'placeholder' => 'Выберите город ...',
                        'id' => 'idCity',
                        ],
                'pluginOptions' => [
                        'allowClear' => true,
                ]
            ]),
            
        ],
        [
            'attribute' => 'phone',
        ],
        [
            'attribute' => 'status',
            'value' => function ($model) {
                return Orders::$statusList[$model->status];
            },
            'filter' => Select2::widget([
                'model' => $searchModel,
                'data' => Orders::$statusList,
                'attribute' => 'status',
                'options' => [
                    'placeholder' => 'Выберите статус ...',
                    'id' => 'statusName',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]),
        ],
        [
            'attribute' => 'paymentType',
            'options' => ['width'=>'220px'],
            'value' => function (Orders $model) {
                return $model->payment ? $model->payment->paymentType->title : '';
            },
            'filter' => Select2::widget([
                'model' => $searchModel,
                'data' => UserPaymentType::getAll(Yii::$app->user->id),
                'attribute' => 'paymentType',
                'options' => [
                    'placeholder' => 'Выберите тип ...',
                    'id' => 'paymentType',
                ],
                'pluginOptions' => ['allowClear' => true],
            ]),
        ],
        [
            'attribute' => 'dateCreate',
            'options' => ['width'=>'150px'],
            'value' => function ($model) {
                return ($model->dateCreate)? date('d-m-Y H:i:s', $model->dateCreate) : '';
            },
            'filter' => false
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'options' => ['width'=>'70px'],
            'template'=>'{view}',
        ],
    ],
]); ?>