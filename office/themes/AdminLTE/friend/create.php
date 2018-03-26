<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\models\Friend;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Friend */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
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
                    return $model->username;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'data' => ArrayHelper::map(User::find()->where('id <> '.Yii::$app->user->id)->orderBy('id ASC')->all(),'id','username'),
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
                    if($model->photoUrl){
                         return '<img src=' . \Yii::$app->files->getUrl($model, 'photoUrl', 100) . ' " >';
                    }
                    else return '';
                },
                'filter' => false,
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    $friend = Friend::find()->where(['idUser'=>Yii::$app->user->id, 'idFriend'=>$model->id])->one();
                    if($friend){
                        return Account::$types[$friend ->status];
                    }
                    else{
                        $friend = Friend::find()->where(['idUser'=>$model->id, 'idFriend'=>Yii::$app->user->id, 'status'=>  Account::TYPE_FRIEND_INVITED])->one();
                        if($friend){
                            return 'Заявка от пользователя';
                        }
                        else return '';   
                    } 
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
                'template'=>' {delete}',
                'buttons' => [
                    'delete' => 'actionFriend',
                ],
            ],
        ],
    ]); 
                
    function actionFriend($url, $model){
            $friend = Friend::find()->where(['idUser'=>Yii::$app->user->id, 'idFriend'=>$model->id])->one();
            $offer = Friend::find()->where(['idUser'=>$model->id, 'idFriend'=>Yii::$app->user->id])->one();
                   
                   if(!$friend && !$offer){
                       return Html::a('Пригласить', ['create', 'id' => $model->id], ['class' => 'btn btn-success']);
                   }
                   
                   if(!$friend){
                       return Html::a(
                            '<span class="glyphicon glyphicon-plus"></span>', 
                            Yii::$app->urlManager->createUrl(['friend/offer', 
                            'id' => $offer->id, 'do' => 'take']), ['title'=>'Принять']
                        ).' '.
                            Html::a(
                                 '<span class="glyphicon glyphicon-trash"></span>', 
                                 Yii::$app->urlManager->createUrl(['friend/offer', 
                                 'id' => $offer->id, 'do' => 'refuse']), [
                                     'title'=>'Отказаться',
                                     'data-confirm'=>'Are you sure you want to delete this item?',
                                     'data-method'=>'post',
                                     'data-pjax'=>0
                                 ]
                             );
                   }
                   
                   if($friend->status < Account::TYPE_FRIEND_REMOVED ){
                       return Html::a('Удалить', 
                                Yii::$app->urlManager->createUrl(['friend/delete', 
                                'id' => $friend->id]), [
                                    'class' => 'btn btn-danger',
                                    'title'=>'Удалить из друзей', 
                                    'data-confirm'=>'Are you sure you want to delete this item?',
                                    'data-method'=>'post',
                                    'data-pjax'=>0
                                ]);
                   }
                   else {
                       return Html::a(
                            'Восстановить', 
                            Yii::$app->urlManager->createUrl(['friend/restore-friend', 
                            'id' => $friend->id]), ['title'=>'Восстановить в друзья', 'class' => 'btn btn-primary',]
                        );
                   } 
    }            
    
    ?>

</div>
