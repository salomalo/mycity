<?php

use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\LogParseBusiness */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Log Parse Businesses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-parse-business-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'options' => ['width'=>'100px'],
            ],
            'title',
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a href="http://www.0629.com.ua'.$model->url.'" target="_blank">'.$model->url.'</a>' ;
                }
            ],     
            'message:ntext',
            [
                'attribute' => 'isFail',
                'options' => ['width'=>'70px'],
                'value' => function ($model) {
                    return $model->isFail ? '<span class="glyphicon glyphicon-remove"></span>' : '<span class="glyphicon glyphicon-ok"></span>';
                },
                'format' => 'html',
                'label' => 'Статус',
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => [0 => 'Без ошибок', 1 => 'Ошибка'],
                    'attribute' => 'isFail',
                    'options' => ['placeholder' => 'Select...', 'id' => 'isFail'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            'dateCreate',

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
            ],
            //['class' => 'yii\grid\CheckboxColumn']
        ],
    ]); ?>

</div>
