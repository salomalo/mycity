<?php

use common\models\ScheduleKino;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use common\models\Afisha;
use yii\helpers\VarDumper;
use \common\models\City;
use \common\models\Business;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ScheduleKino */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model ScheduleKino */

$this->title = 'Расписание фильмов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-kino-index">

    <p><?= Html::a('Добавить расписание к фильму', ['create'], ['class' => 'btn btn-success']) ?></p>

    <?php
    $where = ['type' => 1, 'idCity' => ($searchModel->idCity) ? $searchModel->idCity : Yii::$app->params['activeCitysBackend']];
    ?>
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
                'attribute' => 'idAfisha',
                'value' => function ($model) {
                    return isset($model->afisha->title) ? $model->afisha->title : '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => Afisha::getAll(['isFilm' => 1]),
                    'attribute' => 'idAfisha',
                    'options' => [
                            'placeholder' => 'Select afisha ...',
                            'id' => 'idAfisha',
                            //'multiple' => true,
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],
            [
                'format' => 'html',
                'options' => ['width'=>'80px'],
                'filter' =>false,
                'value' => function ($model) {
                     if($model->afisha->image){
                         return '<img src=' . \Yii::$app->files->getUrl($model->afisha, 'image', 70) . ' " >';
                     }
                     else return '';
                },
            ],
            [
                'attribute' => 'dateStart',
                'options' => ['width'=>'120px'],
                'filter' =>false,
            ],
            [
                'attribute' => 'times',
                'format' => 'html',
                'options' => ['width'=>'150px'],
                'value' => function (ScheduleKino $model) {
                    return
                        '<b>время:</b> ' . $model->getTimes($model->times)
                        . ($model->times2D ? ('<br><b>2D:</b> ' . $model->getTimes($model->times2D)) : '')
                        . ($model->times3D ? ('<br><b>3D:</b> ' . $model->getTimes($model->times3D)) : '');
                },
            ],
            [
                'attribute' => 'idCity',
                'value' => function ($model) {
                    return isset($model->city->title) ? $model->city->title : '';
                },
                'options' => ['width' => '150px'],
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => City::getAll(['id' => Yii::$app->params['activeCitysBackend']]),
                    'attribute' => 'idCity',
                    'options' => [
                        'placeholder' => 'Выберите город',
                        'id' => 'idCity',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]),
            ],
            [
                'attribute' => 'idCompany',
                'label' => 'Кинотеатр',
                'value' => function ($model) {
                    return isset($model->company->title) ? $model->company->title : '';
                },
                'options' => ['width' => '150px'],
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => Business::getBusinessList($where, false),
                    'attribute' => 'idCompany',
                    'options' => [
                        'placeholder' => 'Выберите кинотеатр города',
                        'id' => 'idCompany',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'70px'],
            ],
        ],
    ]); ?>
</div>
