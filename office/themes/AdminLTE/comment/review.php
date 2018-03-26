<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Comment */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Comments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'id',
                'options' => ['width'=>'80px'],
            ],
            //'idUser',
            'text:ntext',
            //'type',
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    return $model->getType($model->type);
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => common\models\Comment::$types,
                    'attribute' => 'type',
                    'options' => [
                        'placeholder' => 'Select a type ...',
                        'id' => 'type',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]),
            ],
            'pid',
            //'parentId',
            [
                'attribute' => 'parentId',
                'value' => function ($model) {
                    return ($model->parentId)? $model->parentId : '';
                },
            ],
            // 'rating',
            // 'ratingCount',
            // 'lastIpLike',
            // 'lastIpRating',
            // 'like',
            // 'unlike',
            // 'dateCreate',

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
