<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\City;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\WidgetCityPublic */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Widget City Publics';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vk-widget-city-public-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Widget City Public', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'city_id',
                'value' => function ($model) {
                    return ($model->city) ? $model->city->title : '';
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => Yii::$app->params['adminCities']['select'],
                    'attribute' => 'city_id',
                    'options' => [
                        'placeholder' => 'Select a city ...',
                        'id' => 'city_id',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]),
            ],
            'group_id',
            'width',
            'height',
            'network',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
