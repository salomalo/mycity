<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Friend */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Удалённые друзья';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="friend-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options' => ['width'=>'70px'],
            ],
            [
                'attribute' => 'idFriend',
                'value' => function ($model) {
                    return $model->getUserFriend($model->idFriend)->username;
                },
                'filter' => false,
            ],
            [
                'attribute' => 'foto',
                'format' => 'html',
                'options' => ['width'=>'120px'],
                'value' => function ($model) {
                    $friend = User::findOne($model->idFriend);
                    if($friend->photoUrl){
                         return '<img src=' . \Yii::$app->files->getUrl($friend, 'photoUrl', 100) . ' " >';
                    }
                    else return '';
                },
                'filter' => false,
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'100px'],
                'template'=>'{view} {delete}',
                'buttons' => [
                    'view' => 'take',
                    'delete' => 'refuse',
                ],
            ],
        ],
    ]);
    
    function take($url, $model){
            return Html::a(
                '<span class="glyphicon glyphicon-plus"></span>', 
                Yii::$app->urlManager->createUrl(['friend/restore-friend', 
                'id' => $model->id]), ['title'=>'Восстановить в друзья']
            );
    }
    
    function refuse($url, $model){
            return Html::a(
                '<span class="glyphicon glyphicon-trash"></span>', 
                Yii::$app->urlManager->createUrl(['friend/delete', 
                'id' => $model->id, 'full' => true]), [
                    'title'=>'Удалить из списка',
                    'data-confirm'=>'Are you sure you want to delete this item?',
                    'data-method'=>'post',
                    'data-pjax'=>0
                ]
            );
    }
    
    ?>

</div>
