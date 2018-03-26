<?php

use common\models\City;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\Region;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\City */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-index">

    <p>
        <?= Html::a('Create City', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'idRegion',
                'options' => ['width' => '300px'],
                'value' => function ($model) {
                    return ($model->region) ? $model->region->title : '';
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(Region::find()->all(),'id','title'),
                    'attribute' => 'idRegion',
                    'options' => [
                            'placeholder' => 'Select a region ...',
                            'id' => 'idRegion',
                            //'multiple' => true,
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],
            [
                'attribute' => 'title',
                'value' => function ($model) {
                    return Html::decode($model->title);
                },
            ],
            [
                'attribute' => 'code',
                'options' => ['width'=>'70px'],
            ],
            'subdomain',
            [
                'attribute' => 'main',
                'value' => function ($model) {
                    return City::$status[$model->main];
                },
                'filter'    => [
                    City::ACTIVE => City::$status[City::ACTIVE],
                    City::PENDING => City::$status[City::PENDING],
                    City::CLOSED => City::$status[City::CLOSED],
                ],
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
