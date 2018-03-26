<?php

use common\models\Advertisement;
use common\models\City;
use common\models\User;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Advertisement */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('advertisement', 'Advertisements');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advertisement-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('advertisement', 'Create Advertisement'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'options' => ['width'=>'50px'],
            ],
            [
                'attribute' => 'city_id',
                'options' => ['width' => '200px'],
                'value' => function (Advertisement $model) {
                    return $model->city ? $model->city->title : $model->city_id;
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => Yii::$app->params['adminCities']['select'],
                    'attribute' => 'city_id',
                    'options' => ['placeholder' => 'Выберите город'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'user_id',
                'options' => ['width' => '200px'],
                'value' => function (Advertisement $model) {
                    return $model->user ? $model->user->username : $model->idUser;
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => User::getAll(),
                    'attribute' => 'user_id',
                    'options' => [
                        'placeholder' => 'Выберите пользователя',
                        'id' => 'user_id',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'position',
                'options' => ['width' => '200px'],
                'value' => function (Advertisement $model) {
                    return $model->positionLabel;
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => Advertisement::$positions,
                    'attribute' => 'position',
                    'options' => [
                        'placeholder' => 'Выберите позицию',
                        'id' => 'position',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'status',
                'options' => ['width' => '200px'],
                'value' => function (Advertisement $model) {
                    return $model->statusLabel;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => Advertisement::$statuses,
                    'attribute' => 'status',
                    'options' => [
                        'placeholder' => 'Выберите статус',
                        'id' => 'status',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            'title',
            [
                'attribute' => 'date_start',
                'options' => ['width' => '200px'],
                'format' => 'date',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date_start',
                    'options' => ['placeholder' => 'Выберите начало'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'format' => 'dd-mm-yyyy',
                        'language' => 'ru',
                    ],
                ]),
            ],
            [
                'attribute' => 'date_end',
                'options' => ['width' => '200px'],
                'format' => 'date',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date_end',
                    'options' => ['placeholder' => 'Выберите окончание'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'format' => 'dd-mm-yyyy',
                        'language' => 'ru',
                    ],
                ]),
            ],
            [
                'label' => 'Статус выполнения',
                'value' => function (Advertisement $model) {
                    return $model->timeStatusLabel;
                },
                'options' => ['width' => '200px'],
                'format' => 'html',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => Advertisement::$time_statuses_text,
                    'attribute' => 'time_status',
                    'options' => [
                        'placeholder' => 'Выберите статус',
                        'id' => 'time_status',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
