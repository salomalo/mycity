<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Comment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('List', ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            //'idUser',
            [
                'attribute' => 'idUser',
                'value' => $model->author->username,
            ],
            'text:ntext',
            //'type',
            [
                'attribute' => 'type',
                'value' => $model->getType($model->type),
            ],
            'pid',
            'parentId',
            'like',
            'unlike',
            'lastIpLike',
            'rating',
            'ratingCount',
            'lastIpRating',
            'dateCreate',
        ],
    ]) ?>

</div>
