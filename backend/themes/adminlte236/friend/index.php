<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Friend */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Friends';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="friend-index">


    <p>
        <?= Html::a('Create Friend', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'idUser',
                'value' => function ($model) {
                    return $model->user->username;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(User::find()->orderBy('id ASC')->all(),'id','username'),
                    'attribute' => 'idUser',
                    'options' => [
                            'placeholder' => 'Select a user ...',
                            'id' => 'idUser',
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],
            [
                'attribute' => 'idFriend',
                'value' => function ($model) {
                    return ($friend = $model->getUserFriend($model->idFriend))? $friend->username : '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(User::find()->orderBy('id ASC')->all(),'id','username'),
                    'attribute' => 'idFriend',
                    'options' => [
                            'placeholder' => 'Select a user ...',
                            'id' => 'idFriend',
                            ],
                    'pluginOptions' => [
                            'allowClear' => true,
                    ]
                ]),
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return User::$types[$model ->status];
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => User::$types,
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
