<?php

use common\models\City;
use common\models\ViewCount;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var $this yii\web\View
 * @var $searchModel common\models\search\ViewCount
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = 'Просмотры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="view-count-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('Добавить просмотры', ['create'], ['class' => 'btn btn-success']) ?></p>

    <?php
    $cities = City::getAll(['id' => Yii::$app->params['activeCitysBackend']]);
    $cities[-1] = 'Не задано';
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Месяц',
                'value' => function (ViewCount $model) {
                    return $model->year . '-' . $model->month;
                }
            ],
            [
                'attribute' => 'category',
                'value' => function (ViewCount $model) {
                    return $model->categoryLabel;
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => ViewCount::$labels,
                    'attribute' => 'category',
                    'options' => [
                        'placeholder' => 'Select a category ...',
                        'id' => 'category',
                    ],
                    'pluginOptions' => ['allowClear' => true]
                ]),
            ],
            [
                'attribute' => 'item_id',
                'value' => function (ViewCount $model) {
                    return $model->itemTitle;
                },
                'format' => 'html',
                'filter' => false,
            ],
            [
                'attribute' => 'city_id',
                'value' => function (ViewCount $model) {
                    return $model->city ? $model->city->title : null;
                },
                'format' => 'html',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => $cities,
                    'attribute' => 'city_id',
                    'options' => [
                        'placeholder' => 'Select a city ...',
                        'id' => 'city_id',
                    ],
                    'pluginOptions' => ['allowClear' => true]
                ]),
            ],
            [
                'attribute' => 'value',
                'filter' => false,
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
