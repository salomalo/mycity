<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PostCategory */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Post Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-category-index">

    <p>
        <?= Html::a('Create Post Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            [
                'attribute' => 'image',
                'format' => 'html',
                'filter' =>false,
                'value' => function ($model) {
                     if($model->image){
                         return '<img src=' . \Yii::$app->files->getUrl($model, 'image', 100) . ' " >';
                     }
                     else return '';
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
