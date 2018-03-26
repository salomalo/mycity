<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\Account $searchModel
 */

$this->title = 'Account';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
//        'showHeader' => false,
        'summary'=>false,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'username',
            'email:email',
            //'password_hash',
            [
                'attribute' => 'photoUrl',
                'format' => 'html',
                'filter' =>false,
                'value' => function ($model) {
                     if($model->photoUrl){
                         return '<img src=' . \Yii::$app->files->getUrl($model, 'photoUrl', 100) . ' " >';
                     }
                     else return '';
                },
            ],
            'type',
            // 'vkID',
            // 'vkToken',
            // 'dateCreate',

            [   
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{update}',
            ],
        ],
    ]); ?>

</div>
