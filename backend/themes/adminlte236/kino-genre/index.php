<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\KinoGenre */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Жанры кино';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kino-genre-index">

    <p>
        <?= Html::a('Create Kino Genre', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'url',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
