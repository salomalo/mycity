<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Tag */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tags';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tag', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options' => ['width'=>'70px']
            ],

            [
                'attribute' => 'id',
                'options' => ['width'=>'100px'],
            ],
            
            'title',

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'70px']
            ],
        ],
    ]); ?>

</div>
