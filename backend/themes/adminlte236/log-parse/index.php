<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var common\models\search\LogParse $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Логи парсера';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-parse-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],  
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a href="http://hotline.ua'.$model->url.'" target="_blank">'.$model->url.'</a>' ;
                }
            ],          
            'idProduct',
            'message:ntext',
            [
                'attribute' => 'isFail',
                'options' => ['width'=>'60px'],
                'value' => function ($model) {
                    return $model->isFail ;
                }
            ],
            'dateCreate',
        ],
    ]); ?>

</div>
