<?php

use common\models\Orders;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use common\models\City;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\WorkResume */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('order', 'My_Orders');
$this->params['breadcrumbs'][] = $this->title;
?>


<?= GridView::widget([

        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
    'options' => ['class' => 'grid-view grid-view-border-top'],
    'layout'=>"<div class=\"box-body\">{items}</div>\n<div class=\"box-footer clearfix\"><div class='pull-right'>{pager}</div></div>\n{summary}",
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'label' => '№ заказа',
                'options' => ['width'=>'100px'],
            ],
//            [
//                'attribute' => 'idCategory',
//                'label' => 'Категория',
//                'value' => function ($model) {
//                    return $model->category->title;
//                },
//                'filter' => Select2::widget([
//                    'model' => $searchModel,
//                    'data' => ArrayHelper::map(WorkCategory::find()->all(),'id','title'),
//                    'attribute' => 'idCategory',
//                    'options' => [
//                            'placeholder' => 'Выберите категорию ...',
//                            'id' => 'idCategory',
//                            ],
//                    'pluginOptions' => [
//                            'allowClear' => true,
//                    ]
//                ]),
//            ],
            
//            [
//                'attribute' => 'photoUrl',
//                'label' => 'Картинка',
//                'format' => 'html',
//                'filter' =>false,
//                'value' => function ($model) {
//                     if($model->photoUrl){
//                         return '<img src=' . \Yii::$app->files->getUrl($model, 'photoUrl', 100) . ' " >';
//                     }
//                     else return '';
//                },
//            ],
            [
                'attribute' => 'idCity',
                'options' => ['width'=>'200px'],
                'value' => function ($model) {
                    return $model->city->title;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(City::find()->all(),'id','title'),
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
                'attribute' => 'address',
            ],
            [
                'attribute' => 'fio',
            ],
            [
                'format' => 'html',
                'attribute' => 'status',
                'value' => function ($model) {
                    return '<div style="color: #3c8dbc; "><strong>' . Orders::$statusList[$model->status] . '</strong></div>';
                },
            ],
            [
                'attribute' => 'paymentType',
                'value' => function ($model) {
                    return ($model->payment)? $model->payment->paymentType->title : '';
                }
            ],
            [
                'attribute' => 'dateCreate',
                'options' => ['width'=>'150px'],
                'value' => function ($model) {
                    return ($model->dateCreate)? date('d-m-Y H:i:s', $model->dateCreate) : '';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'70px'],
                'template'=>'{view}',
            ],
        ],
    ]); ?>