<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\BusinessTemplate */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Business Templates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-template-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить шаблон', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'alias',
            [
                'attribute' => 'img',
                'value' => function($model) {
                    return Html::img(Yii::$app->files->getUrl($model, 'img', 100));
                },
                'format' => 'raw',
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
