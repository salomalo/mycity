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

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Найти друзей', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'idFriend',
                'value' => function ($model) {
                    return $model->getUserFriend($model->idFriend)->username;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(User::find()
                            ->select(['id','username'])
                            ->where(['id' => $arrFriend])
                            ->orderBy('id ASC')->all(),'id','username'),
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
                'attribute' => 'foto',
                'format' => 'html',
                'options' => ['width'=>'120px'],
                'value' => function ($model) {
                    $friend = Account::findOne($model->idFriend);
                    if($friend->photoUrl){
                         return '<img src=' . \Yii::$app->files->getUrl($friend, 'photoUrl', 100) . ' " >';
                    }
                    else return '';
                },
                'filter' => false,
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return Account::$types[$model ->status];
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

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'100px'],
                'template'=>'{view} {delete}',
                'buttons' => [
                    'delete' => 'deleteFriend',
                ],
            ],
        ],
    ]); 
                
    function deleteFriend($url, $model){
        
        if($model->status < User::TYPE_FRIEND_REMOVED){
            return Html::a(
                '<span class="glyphicon glyphicon-trash"></span>', 
                Yii::$app->urlManager->createUrl(['friend/delete', 
                'id' => $model->id]), [
                    'title'=>'Удалить из друзей', 
                    'data-confirm'=>'Are you sure you want to delete this item?',
                    'data-method'=>'post',
                    'data-pjax'=>0
                ]
            );
        }
        else {
            return Html::a(
                '<span class="glyphicon glyphicon-plus"></span>', 
                Yii::$app->urlManager->createUrl(['friend/restore-friend', 
                'id' => $model->id]), ['title'=>'Восстановить в друзья']
            ).
                Html::a(
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
            
    }            
    
    ?>

</div>
