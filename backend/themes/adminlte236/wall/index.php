<?php

use yii\helpers\Html;
use yii\grid\GridView;;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\City;
use common\models\Wall;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Wall */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Walls';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wall-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Html::a('Create Wall', ['create'], ['class' => 'btn btn-success']) ?></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'options' => ['width'=>'100px'],
            ],
            [
                'attribute' => 'pid',
                'options' => ['width'=>'100px'],
            ],
            [
                'attribute' => 'type',
                'options' => ['width' => '150px'],
                'value' => function ($model) {
                    return isset(Wall::$types[$model->type]) ? Wall::$types[$model->type] : $model->type;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => Wall::$types,
                    'attribute' => 'type',
                    'options' => [
                        'placeholder' => 'Select a type ...',
                        'id' => 'type',
                    ],
                    'pluginOptions' => ['allowClear' => true]
                ]),
            ],
            [
                'attribute' => 'idCity',
                'options' => ['width' => '150px'],
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

            'title:ntext',
            [
                'attribute' => 'image',
                'format' => 'html',
                'filter' =>false,
                'value' => function ($model) {
                     if($model->image){
                         return Html::img($model->image, ['width' => '100']);
                     }
                     else return '';
                },
            ],

            'description:html',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
