<?php
/**
 * @var $this yii\web\View
 * @var $searchModel common\models\search\Counter
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use common\models\Counter;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Счетчики';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="counter-index">
    <p><?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'attribute' => 'for_office',
                'value' => function (Counter $model) {
                    return $model->forOfficeColorLabel;
                },
                'format' => 'html',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => Counter::$forOptions,
                    'attribute' => 'for_office',
                    'options' => ['placeholder' => 'Выберите фильтр'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'for_main',
                'value' => function (Counter $model) {
                    return $model->forMainColorLabel;
                },
                'format' => 'html',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => Counter::$forOptions,
                    'attribute' => 'for_main',
                    'options' => ['placeholder' => 'Выберите фильтр'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'for_all_cities',
                'value' => function (Counter $model) {
                    return $model->forAllCitiesColorLabel;
                },
                'format' => 'html',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => Counter::$forOptions,
                    'attribute' => 'for_all_cities',
                    'options' => ['placeholder' => 'Выберите фильтр'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            'created_at:date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
