<?php

use common\models\Lead;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Lead */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказ консультации';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lead-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Lead', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'phone',
            'description',
            [
                'attribute' => 'status',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'data' => Lead::$statusTypes,
                    'options' => ['placeholder' => 'Выберите статус'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
                'value' => function (\common\models\Lead $model) {
                    if (array_key_exists($model->status, Lead::$statusTypesHtml)) {
                        return Lead::$statusTypesHtml[$model->status];
                    } else {
                        return '';
                    }

                },
                'format' => 'html',
            ],
            [
                'attribute' => 'date_create',
                'options' => ['width' => '200px'],
                'filter' => \yii\jui\DatePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'date_create',
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd'
                ]),
                'format' => 'html',
            ],
            'utm_source',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
            ],
        ],
    ]); ?>
</div>
