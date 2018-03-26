<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use common\models\AfishaCategory;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AfishaCategory */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ($isFilm)? 'Категории Кино' : 'Категории Афиши';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="afisha-category-index">


    <p>
        <?= Html::a(($isFilm)? 'Добавить категорию кино' : 'Добавить категорию афиши', 
            ['create', 'isFilm' => ($isFilm)? 1 : NULL], 
            ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options' => ['width'=>'70px'],
            ],

            [
                'attribute' => 'id',
                'options' => ['width'=>'70px'],
            ],
            'title',
            [
                'attribute' => 'pid',
                'options' => ['width'=>'300px'],
                'value' => function ($model) {
                    return ($model->parent)? $model->parent->title : '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(AfishaCategory::find()
                            ->where(['pid' => null])
                            ->andWhere(['isFilm' => ($isFilm)? 1 : 0])
                            ->all(),'id','title'),
                    'attribute' => 'pid',
                    'options' => [
                            'placeholder' => 'Select a pid ...',
                            'id' => 'pid',
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],
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
                'attribute' => 'order',
                'options' => ['width'=>'70px'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'70px'],
            ],
        ],
    ]); ?>

</div>
