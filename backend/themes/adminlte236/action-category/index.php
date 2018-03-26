<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CurrencActionCategory */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Action Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-category-index">


    <p>
        <?= Html::a('Create Action Category', ['create'], ['class' => 'btn btn-success']) ?>
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
            'title',
            [
                'attribute' => 'image',
                'format' => 'html',
                'options' => ['width'=>'120px'],
                'filter' =>false,
                'value' => function ($model) {
                     if($model->image){
                         return '<img src=' . \Yii::$app->files->getUrl($model, 'image', 100) . ' " >';
                     }
                     else return '';
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'70px'],
            ],
        ],
    ]); ?>

</div>
