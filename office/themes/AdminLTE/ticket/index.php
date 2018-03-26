<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Ticket;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Ticket */
/* @var $dataProvider yii\data\ActiveDataProvider */

//echo "<pre>";
//print_r($searchModel);
//echo "</pre>"; die();

$this->title = Yii::t('ticket', 'Questions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">
    <p>
        <?= Html::a(Yii::t('ticket', 'Add_questions'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'grid-view grid-view-border-top'],
        'layout'=>"<div class=\"box-body\">{items}</div>\n<div class=\"box-footer clearfix\"><div class='pull-right'>{pager}</div></div>\n{summary}",
        'columns' => [
          //  ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'idUser',
            [
                'attribute' => 'id',
                'label' => '№',
                'options' => ['width'=>'150px'],                
                'value' => function ($model) {
                    return $model->id;
                },
            ],            
            [
                'attribute' => 'title',
                'label' => 'Заголовок',
                'value' => function ($model) {
                    return $model->title;
                },
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
                'template'=>'{view}',
            ],
        ],
    ]); ?>

</div>
