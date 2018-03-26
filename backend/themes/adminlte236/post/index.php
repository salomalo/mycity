<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use common\models\PostCategory;
use common\models\City;
use yii\helpers\ArrayHelper;
use common\models\Post;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Post */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новости';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <p>
        <?= Html::a('Добавить новость', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'idCity',
                'options' => ['width' => '200px'],
                'value' => function ($model) {
                    return isset($model->city->title)? $model->city->title : '';
                },
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'data' => Yii::$app->params['adminCities']['select'],
                    'attribute' => 'idCity',
                    'options' => [
                        'placeholder' => 'Select a city ...',
                        'id' => 'idCity',
                    ],
                    'pluginOptions' => ['allowClear' => true]
                ]),
            ],
            [
                'attribute' => 'allCity',
                'value' => function($model){
                    return ($model->allCity) ? 'Да' : 'Нет';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => [false => 'Нет', true => 'Да',],
                    'attribute' => 'allCity',
                    'options' => ['placeholder' => 'Select ...',],
                    'pluginOptions' => ['allowClear' => true,]
                ]),
            ],
            [
                'attribute' => 'onlyMain',
                'value' => function($model){
                    return ($model->onlyMain) ? 'Да' : 'Нет';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => [false => 'Нет', true => 'Да',],
                    'attribute' => 'onlyMain',
                    'options' => ['placeholder' => 'Select ...',],
                    'pluginOptions' => ['allowClear' => true,]
                ]),
            ],
            [
                'attribute' => 'idUser',
                'label' => 'Автор',
                'options' => ['width' => '150px'],
                'value' => function ($model) {
                    return isset($model->user->username) ? $model->user->username : $model->idUser;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => \common\models\User::getAll(),
                    'attribute' => 'idUser',
                    'options' => [
                        'placeholder' => 'Выберите пользователя',
                        'id' => 'idUser',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]),
            ],
            [
                'attribute' => 'idCategory',
                'options' => ['width' => '200px'],
                'value' => function ($model) {
                    return isset($model->category->title) ? $model->category->title : '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => PostCategory::getAll(),
                    'attribute' => 'idCategory',
                    'options' => [
                        'placeholder' => 'Select a category ...',
                        'id' => 'idCategory',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]),
            ],
            'title',
            [
                'attribute' => 'image',
                'format' => 'html',
                'filter' => false,
                'value' => function ($model) {
                     if($model->image){
                         return '<img  width="100"  src=' . \Yii::$app->files->getUrl($model, 'image', 90) . ' " >';
                     }
                     else return '';
                },
            ],
            [
                'attribute' => 'shortText',
                'format' => 'text',
                'value' => function ($model) {
                    return html_entity_decode(strip_tags($model->shortText), ENT_QUOTES);
                },
            ],
            [
                'attribute' => 'status',
                'options' => ['width' => '200px'],
                'value' => function ($model) {
                    return isset(Post::$types[$model->status]) ? Post::$types[$model->status] : '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => Post::$types,
                    'attribute' => 'status',
                    'options' => [
                        'placeholder' => 'Select a status ...',
                        'id' => 'status',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]),
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
