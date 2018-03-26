<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Provider */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Providers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="provider-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Provider', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'description',
            [
                'attribute' => 'user_id',
                'format' => 'html',
                'value' => function (\common\models\Provider $model) {
                    $url = Url::to(['/user/view', 'id' => $model->user_id]);
                    $link = Html::a(isset($model->user->username) ? $model->user->username : $model->user_id, $url);
                    return $link;
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
