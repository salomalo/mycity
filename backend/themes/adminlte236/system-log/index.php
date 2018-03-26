<?php

use common\models\SystemLog;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SystemLog */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'System Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-log-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '100px'],],

            [
                'attribute' => 'id',
                'options' => ['width' => '100px'],
            ],
            [
                'attribute' => 'dateCreate',
                'options' => ['width' => '200px'],
                'filter' => \yii\jui\DatePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'dateCreate',
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd'
                ]),
                'format' => 'html',
            ],
            [
                'attribute' => 'description',
                'filter' => false,
//                'format' => 'raw'
            ],
            [
                'attribute' => 'status',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'data' => [
                        SystemLog::STATUS_WARNING => SystemLog::STATUS_WARNING,
                        SystemLog::STATUS_ERROR => SystemLog::STATUS_ERROR,
                        SystemLog::STATUS_INFO => SystemLog::STATUS_INFO,
                    ],
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => ['allowClear' => true]
                ]),
//                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'70px'],
                'template'=>'{view}{delete}',
            ],
        ],
    ]); ?>

</div>
