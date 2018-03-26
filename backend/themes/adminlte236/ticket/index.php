<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use common\models\Ticket;
use common\models\User;
use kartik\widgets\Select2;
use common\models\City;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Ticket */
/* @var $dataProvider yii\data\ActiveDataProvider */

//echo "<pre>";
//print_r($searchModel);
//echo "</pre>"; die();

$this->title = 'Вопросы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
          //  ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'idUser',
            [
                'attribute' => 'id',
                'label' => '№',
                'options' => ['width'=>'100px'],                
                'value' => function ($model) {
                    return $model->id;
                },
            ],            
            [
                'attribute' => 'idCity',
                'options' => ['width'=>'200px'],                
                'value' => function ($model) {
                    return ($model->city)? $model->city->title : '';
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(City::find()->where(['id' => Yii::$app->params['activeCitysBackend']])->all(),'id','title'),
                    'attribute' => 'idCity',
                    'options' => [
                            'placeholder' => 'Выберите город ...',
                            'id' => 'idCity',
                            //'multiple' => true,
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],            
            [
                'attribute' => 'title',
                'label' => 'Заголовок',
                'value' => function ($model) {
                    return $model->title;
                },
            ],
            [
                'attribute' => 'idUser',
                'label' => 'Пользователь',
                'value' => function ($model) {
                    return ($model->user)? $model->user->username : '';
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(User::find()->all(),'id','username'),
                    'attribute' => 'idUser',
                    'options' => [
                            'placeholder' => 'Выберите пользователя ...',
                            'id' => 'idUser',
                            //'multiple' => true,
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],
            [
                'attribute' => 'type',
                'label' => 'Тип тикета',
                'options' => ['width'=>'200px'],
                'value' => function ($model) {
                    return Ticket::$types[$model->type];
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => Ticket::$types,
                    'attribute' => 'type',
                    'options' => [
                            'placeholder' => 'Выберите тип ...',
                            'id' => 'type',
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],                       
            [
                'attribute' => 'status',
                'label' => 'Статус',
                'options' => ['width'=>'200px'],
                'value' => function ($model) {
                    return Ticket::$statuses[$model->status];
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => Ticket::$statuses,
                    'attribute' => 'status',
                    'options' => [
                            'placeholder' => 'Выберите статус ...',
                            'id' => 'status',
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],
            //'email:email', 
            [
                'attribute' => 'dateCreate',
                'label' => 'Дата создания',                
                'options' => ['width'=>'150px'],
                'value' => function ($model) {
                    return $model->dateCreate;
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'70px'],
                'template'=>'{view}{update}',
            ],
        ],
    ]); ?>

</div>
