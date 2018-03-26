<?php
use yii\grid\GridView;
use common\models\OrdersAds;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = Yii::t('order', 'List_of_products_by_order');
$this->params['breadcrumbs'][] = $this->title;
?>


<?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel, pid, id ads, count ads, id busin, status
        'columns' => [
           
            [
                'attribute' => 'pid',
                'label' => '№ заказа',
                'options' => ['width'=>'100px'],
            ],
            [
                'attribute' => 'idAds',
                'value' => function ($model) {
                    return ($model->ads)? $model->ads->title : '';
                },
            ],
            [
                'attribute' => 'countAds',
            ],
            [
                'attribute' => 'idBusiness',
                'value' => function ($model) {
                    return ($model->business)? $model->business->title : '';
                },
            ],
//            [
//                'attribute' => 'status',
//                'options' => ['width'=>'200px'],
//                'value' => function ($model) {
//                    return OrdersAds::$statusList[$model->status];
//                },
//            ],
     ],
    ]); ?>